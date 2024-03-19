<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController123 extends Controller
{
    public function paypal(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal')); // Calling paypal.php to user its data
        $paypal_token = $provider->getAccessToken();

        $response = $provider->createOrder([  // enter Data which need to create
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('success'),
                "cancel_url" => route('cancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $request->grandTotal
                    ]
                ]
            ]
        ]);
        // dd($response);
        if (isset($response['id']) && $response['id'] != null) {
            foreach($response['links'] as $link) {
                if ($link['rel'] == 'approve') {
                    return $link['href'];
                }
            }
        } else {
            return redirect()->route('cancel');
        }
    }

    public function success(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypal_token = $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);
        // dd($response);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            
            // Insert data to database
            $payment = new Payment();
            $userId = Auth::user()->id;

            $payment->transaction_id = $response['id'];
            $payment->user_id = $userId;
            $payment->amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $payment->currency = $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'];
            $payment->payment_gateway = 'Paypal';
            $payment->payment_status = 'success'; // Response values are different, need to alter payments column names
            $payment->save();

            Cart::destroy();
            session()->forget('code');
            
            return "Payment is successfull.";
        } else {
            return redirect()->route('cancel');
        }
    }

    public function cancel()
    {
        return "Payment is cancelled.";
    }

}
