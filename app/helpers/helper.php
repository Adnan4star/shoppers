<?php

use App\Mail\OrderEmail;
use App\Models\category;
use App\Models\Country;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;

function getCategories()
{
    return category::orderBy('name','ASC')
        ->with('sub_category')
        ->orderBy('id','DESC')
        ->where('status',1)
        ->where('showHome','Yes')
        ->get();
}

// For order detail page
function getProductImage($product_Id)
{
    return Product::where('id',$product_Id)->first();
}

// For sending order emails frontend and backend
function orderEmail($orderId, $userType="customer")
{
    $order = Order::where('id',$orderId)->with('items')->first();

    if ($userType == 'customer') {
        $subject = 'Thankyou for shopping with us';
        $email = $order->email;
    } else {
        $subject = 'You have received an order';
        $email = env('ADMIN_EMAIL');
    }

    $mailData = [
        'subject' => $subject,
        'order' => $order,
        'userType' => $userType
    ];

    Mail::to($email)->send(new OrderEmail($mailData));
}

// Countries name
function getCountryInfo($id)
{
    $countryName = Country::where('id',$id)->first();
    return $countryName;
}

?>