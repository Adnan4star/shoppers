<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shipping;
use Carbon\Carbon;
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
        $discount = 0;
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
        session()->forget('url.intended');

        $customerAddress = CustomerAddress::where('user_id',Auth::user()->id)->first(); // pre-filling billing form if user has already placed order

        $countries = Country::orderBy('name','ASC')->get();

        // Apply discount coupon code here
        $subTotal = Cart::Subtotal(2,'.','');

        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount/100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }
        // if user address not logged in than shipping charge = 0
        // Calculate Shipping charges
        if ($customerAddress != '') {

            $userCountry = $customerAddress->country_id;
            $shippingInfo = Shipping::where('country_id',$userCountry)->first();

            if ($shippingInfo) {
                $totalQty = 0;
                $totalShippingCharges = 0;
                $grandTotal = 0;

                foreach (Cart::content() as $item) {
                    $totalQty += $item->qty;
                }

                $totalShippingCharges = $totalQty*$shippingInfo->amount;
                $grandTotal = ($subTotal-$discount) + $totalShippingCharges;
            } else {
                $grandTotal = ($subTotal-$discount);
                $totalShippingCharges = 0;
            }

        } else {
            $grandTotal = Cart::Subtotal(2,'.','');
            $totalShippingCharges = 0;
        }
        
        $data['countries'] = $countries;
        $data['customerAddress'] = $customerAddress;
        $data['totalShippingCharges'] = number_format($totalShippingCharges,2);
        $data['grandTotal'] = number_format($grandTotal,2);
        $data['discount'] = number_format($discount,2);

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
            // Shipping charges
            $discount = 0;
            $shipping = 0;
            $discountCodeId = NULL;
            $promoCode = '';
            $subTotal = Cart::subtotal(2,'.',''); // (3 parameters decimal, decimal separator, thousandSeparator)
    
            // Apply discount coupon code here
            if (session()->has('code')) {
                $code = session()->get('code');

                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount/100) * $subTotal;
                } else {
                    $discount = $code->discount_amount;
                }

                $discountCodeId = $code->id;
                $promoCode =  $code->code;
            }

            // Shipping info
            $shippingInfo = Shipping::where('country_id',$request->country)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null){
                $shipping = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shipping;

            } else {
                $shippingInfo = Shipping::where('country_id','others')->first();
                $shipping = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shipping;

            }

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
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
            // Send order Email, function defined in helper.php
            orderEmail($order->id,'customer');

            session()->flash('success','Order placed successfully.');

            Cart::destroy(); // Destroying cart items

            session()->forget('code'); // forget coupon code

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

    public function getOrderSummary(Request $request)
    {
        $subTotal = Cart::subTotal(2,'.','');
        $discount = 0;
        $discountString = '';

        // Apply discount coupon code here
        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount/100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
            // Appending delete button for coupon code
            $discountString='<div class="input-group w-85" id="discount-response">
            <strong class="form-control"> '.session()->get('code')->code.' </strong>
            <div class="input-group-append">
                <button class="btn btn-danger btn-sm" type="button" id="remove-discount">Delete</button>
            </div>
            </div>';
        }
        
    
        if ($request->country_id > 0){

            $shippingInfo = Shipping::where('country_id',$request->country_id)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null){
                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal-$discount) + $shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge,2),
                ]);

            } else {
                $shippingInfo = Shipping::where('country_id','others')->first();
                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal-$discount) + $shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal,2),
                    'discount' => number_format($discount,2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge,2),
                ]);
            }

        } else {

            return response()->json([
                'status' => true,
                'grandTotal' => ($subTotal-$discount),
                'discount' => $discount,
                'discountString' => $discountString,
                'shippingCharge' => 0,
            ]);
        }
    }

    public function applyDiscount(Request $request)
    {
        $code = DiscountCoupon::where('code',$request->code)->first();

        if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid coupon code'
            ]);
        }

        // Check if coupon starts_at is valid
        $now = Carbon::now();

        if ($code->starts_at != '') {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s',$code->starts_at);

            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid coupon code'
                ]);
            } 
        }

        // Check if coupon expires_at is valid
        if ($code->expires_at != '') {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s',$code->expires_at);

            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid coupon code'
                ]);
            } 
        }

        // Check max_uses
        if ($code->max_uses > 0) {
            $couponUsed = Order::where('coupon_code_id',$code->id)->count();

            if ($couponUsed >= $code->max_uses) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid discount coupon'
                ]);
            }
        }
        
        // Check max_uses_user
        if ($code->max_uses_user > 0) {
            $couponUsedByUser = Order::where(['coupon_code_id' => $code->id, 'user_id' => Auth::user()->id])->count(); // comparing user_id from orders and session id of user

            if ($couponUsedByUser >= $code->max_uses_user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Coupon already used.'
                ]);
            }
        }

        $subTotal = Cart::subtotal(2,'.','');
        // Check min_amount
        if ($code->min_amount > 0) {
            if ($subTotal < $code->min_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart minimum amount must be $'.$code->min_amount.'.',
                ]);
            }
        }

        session()->put('code',$code); // Setting code with its array to session
        
        return $this->getOrderSummary($request); // using getOrderSummary function

    }

    public function removeCoupon(Request $request)
    {
        session()->forget('code');
        return $this->getOrderSummary($request);
    }
}
