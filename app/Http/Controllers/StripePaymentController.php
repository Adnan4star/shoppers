<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipping;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;
use Stripe;

class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe(Request $request)
    {
        // customer, address, order 

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

        $user = Auth::user();

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
        if ($request->payment_method == 'stripe') {
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

            $order = new Order();
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
                $orderItem = new OrderItem();
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();

                // Update product stock
                $productData = Product::find($item->id);
                if ($productData->track_qty == 'Yes') {
                    $currentQty = $productData->qty;
                    $updatedQty = $currentQty - $item->qty;
                    $productData->qty = $updatedQty;
                    $productData->save();
                }
                
            }
            // Send order Email, function defined in helper.php
            // orderEmail($order->id,'customer');

            Cart::destroy(); // Destroying cart items

            session()->forget('code'); // forget coupon code

            // session()->flash('success','Order details saved, Waiting for payment confirmation!');
        }

        
        $data['grandTotal'] = $grandTotal;
        $data['orderId'] = $order->id;
        //append order id with view
        
        return view('front.stripe',$data);
    }
    
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        //get order id
        //in if response paid true condition ---> start
        //update order against id
        // return thank you blade ---> end
        $orderId = $request->orderId;
        $userId = Auth::id();

        Stripe\Stripe::setApiKey(config('stripe.stripe_sk'));
        
        $response = Stripe\Charge::create ([
                    "amount" => $request->grandTotal * 100,
                    "currency" => "usd",
                    "source" => $request->stripeToken,
                    "description" => "Test payment from Shoppers.com." 
            ]);

            if ($response->paid == true) {
                Order::where('id', $orderId)->update(
                    [                         
                        'payment_status' => 'paid',
                    ]
                );

                $payment = new Payment();
                $payment->user_id = $userId;
                $payment->transaction_id = $response->id;
                $payment->amount = $response->amount;
                $payment->currency = $response->currency;

                if ($response->status == 'succeeded') {
                    $payment->payment_status = 'success';
                } elseif ($response->status == 'failed') {
                    $payment->payment_status = 'failure';
                } else {
                    $payment->payment_status = 'pending';
                }

                $payment->payment_gateway = "Stripe";
                $payment->save();

                return redirect(url('thankyou/' . $orderId));
            } else {
                session()->flash('error', 'Payment was not successful!');  
                return back();
            }

            session()->flash('success', 'Payment successful!');  
            return back();
        
    }
}