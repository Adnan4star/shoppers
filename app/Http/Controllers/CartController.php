<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

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
        session()->forget('url.intended');

        $countries = Country::orderBy('name','ASC')->get();
        $data['countries'] = $countries;
        return view('front.checkout', $data);
    }
}
