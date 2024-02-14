<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

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
}
