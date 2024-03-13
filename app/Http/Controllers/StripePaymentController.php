<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $data['grand_total'] = $request->grand_total;
        return view('front.stripe',$data);
    }
    
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        Stripe\Stripe::setApiKey(config('stripe.stripe_sk'));
        
            Stripe\Charge::create ([
                    "amount" => 393 * 100,
                    "currency" => "usd",
                    "source" => $request->stripeToken,
                    "description" => "Test payment from itsolutionstuff.com." 
            ]);
        
            session()->flash('success', 'Payment successful!');  
            return back();
        
    }
}