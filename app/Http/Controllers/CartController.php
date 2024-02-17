<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found',
            ]);
        }
        
        if (Cart::count() > 0) {
            // using mindscms library
            // Checking whether product is already added in cart
            // return message product already added in cart
            // if product not found in cart, then add product to cart

            $cartContent = Cart::content(); // Fetching all cart products
            $productAlreadyExists = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExists = true;
                }
            }

            if ($productAlreadyExists == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['image' => (!empty($product->image)) ? $product->image : '']);

                $status = true;
                $message = $product->title. ' added in cart';
            } else {
                $status = false;
                $message = $product->title. ' is already added in cart';
            }

        } else {
            // using mindscms library
            // If cart is empty
            // add product details to cart
            // 4 parameters required, 5th is image as array as optional
            Cart::add($product->id, $product->title, 1, $product->price, ['image' => (!empty($product->image)) ? $product->image : '']);
            $status = true;
            $message = $product->title. ' added successfully';
        }
            return response()->json([
                'status' => $status,
                'message' => $message
            ]);
    }

    public function cart()
    {
        $cartContents = Cart::content();
        // dd($cartContents);
        $data['cartContents'] = $cartContents;

        return view('front.cart',$data);
    }

    public function updateCart(Request $request) 
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId); // Getting row id via library used
        $product = Product::find($itemInfo->id); //id come from Cart::product->id
        
        // check qty available in stock
        if ($product->track_qty == 'Yes') { // product will have track_qty checked in admin to track stock level
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty); // Will update the quantity 
                $message = 'Cart updated successfully.';
                $status  = true;
                session()->flash('success', $message);
            } else {
                $message = 'Requested qty ('.$qty.') not available in stock.';
                $status = false;
                session()->flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty); // Will update the quantity 
            $message = 'Cart updated successfully.';
            $status  = true;
            session()->flash('success', $message);
        }
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request)
    {
        $rowId = $request->rowId;
        $itemInfo = Cart::get($rowId);

        if ($itemInfo == null) {
            $errorMsg = 'Item not found in cart.';
            session()->flash('error', $errorMsg);

            return response()->json([
                'status' => false,
                'message' => $errorMsg
            ]);
        } else {
            Cart::remove($request->rowId);
            $message = 'Item removed successfully.';

            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }
        

        
    }

    public function checkout()
    {
        // If cart empty redirect to cart page
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        // If user not logged in, redirect to login page
        if (Auth::check() == false) {
            
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]); // saving current url of checkout page if user not logged in.
            }
            
            return redirect()->route('account.login');
        }

        $customerAddress = CustomerAddress::where('user_id',Auth::user()->id)->first(); // pre-filling billing form if user has already placed order

        session()->forget('url.intended');
        $countries = Country::orderBy('name','ASC')->get();

        $data['countries'] = $countries;
        $data['customerAddress'] = $customerAddress;
        return view('front.checkout', $data);
    }

    public function processCheckout(Request $request)
    {
        // Step-1 Apply validation
        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'address' => 'required|min:15',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'errors' => $validator->errors()
            ]);
        }

        // Step-2 
        // Save customer Address
        // Another way for saving record, Have to pass two arrays
        // Define fillables in CustomerAddress Model
        $user = Auth::user(); // getting user id

        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id], // 1st array checks does data already exits in db.
            [                         // 2nd array (If record doesnt exist it creates new record otherwise it will update.
                'user_id' => $user->id,
                'first_name' => $request->fname,
                'last_name' => $request->lname,
                'email' => $request->email,
                'phone' => $request->phone,
                'country_id' => $request->country,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'address' => $request->address,
                'apartment' => $request->apartment
            ]
        );
        
        // Step-3 store data in orders table
        if ($request->payment_method == 'cod') {
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2,'.',''); // (3 parameters decimal, decimal separator, thousandSeparator)
            $grandTotal =  $subTotal + $shipping;

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->user_id = $user->id;
            $order->first_name = $request->fname;
            $order->last_name = $request->lname;
            $order->email = $request->email;
            $order->phone = $request->phone;
            $order->country_id = $request->country;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->zip = $request->zip;
            $order->notes = $request->notes;
            $order->save();
            
            // Step-4 store order details in order items table
            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem;
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();
            }
            session()->flash('success','Order placed successfully.');

            Cart::destroy(); // Destroying cart items
            return response()->json([
                'status' => true,
                'orderId' => $order->id,
                'message' => 'Order placed successfully.'
            ]);
            
        } else {
            // Banking
        }

    }

    public function thankyou($id)
    {
        $data['id'] = $id;
        return view('front.thankyou',$data);
    }
}
