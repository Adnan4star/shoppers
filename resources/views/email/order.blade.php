<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Email</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px">
    
    @if($mailData['userType'] == 'customer')
        <h1>Thankyou for shopping with us</h1>
        <h2>Your orderId is: #{{ $mailData['order']->id }} </h2>
    @else
        <h1>You have received an order</h1>
        <h2>OrderId: #{{ $mailData['order']->id }} </h2>
    @endif
    
        <h2>Shipping Address</h2>
    <address>
        <strong>{{$mailData['order']->first_name.' '.$mailData['order']->last_name}}</strong><br>
        {{$mailData['order']->address}}, {{$mailData['order']->apartment}}<br>
        {{$mailData['order']->city}}, {{$mailData['order']->zip}}, {{ getCountryInfo($mailData['order']->country_id)->name }}<br>
        Phone: {{$mailData['order']->phone}}<br>
        Email: {{$mailData['order']->email}}
    </address>

    <h2>Products</h2>
    <table cellpadding="3" cellspacing="3" style="border: 0" width="700">
        <thead>
            <tr style="background: #ccc;">
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>                                        
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData['order']->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>${{ number_format($item->price,2) }} </td>                                        
                    <td> {{ $item->qty }} </td>
                    <td>${{ number_format($item->total,2) }} </td>
                </tr>
            @endforeach
            <tr>
                <th colspan="3" style="text-align: right">Subtotal:</th>
                <td>${{ number_format($mailData['order']->subtotal,2) }}</td>
            </tr>
            <tr>
                <th colspan="3" style="text-align: right">Discount:{{ (!empty($mailData['order']->coupon_code)) ? '('.  $mailData['order']->coupon_code . ')' : ''  }}</th>
                <td>${{ number_format($mailData['order']->discount,2) }}</td>
            </tr>
            <tr>
                <th colspan="3" style="text-align: right">Shipping:</th>
                <td>${{ number_format($mailData['order']->shipping,2) }}</td>
            </tr>
            <tr>
                <th colspan="3" style="text-align: right">Grand Total:</th>
                <td>${{ number_format($mailData['order']->grand_total,2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>