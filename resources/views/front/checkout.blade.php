@extends('front.layouts.app')

@section('content')
    <div class="site-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="border p-4 rounded" role="alert">
                        Returning customer? <a href="#">Click here</a> to login
                    </div>
                </div>
            </div>
            <form action="" method="POST" id="orderForm">
                <div class="row">
                    <div class="col-md-6 mb-5 mb-md-0">
                    <h2 class="h3 mb-3 text-black">Billing Details</h2>
                    <div class="p-3 p-lg-5 border">
                        <div class="form-group">
                            <label for="country" class="text-black">Country <span class="text-danger">*</span></label>
                            <select id="country" name="country" class="form-control">
                                <option value="">Select a country</option>    
                                    @if ($countries->isNotEmpty())
                                        @foreach ($countries as $country)
                                            <option {{ (!empty($customerAddress) && $customerAddress->country_id == $country->id) ? 'selected' : ''}} value="{{ $country->id }}">{{ $country->name }}</option>    
                                        @endforeach
                                    @endif
                            </select>
                            <p></p>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="fname" class="text-black">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="fname" name="fname" value="{{ (!empty($customerAddress)) ? $customerAddress->first_name : ''}}">
                                <p></p>
                            </div>
                            <div class="col-md-6">
                                <label for="lname" class="text-black">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="lname" name="lname" value="{{ (!empty($customerAddress)) ? $customerAddress->last_name : ''}}">
                                <p></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="address" class="text-black">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Street address" value="{{ (!empty($customerAddress)) ? $customerAddress->address : ''}}">
                                <p></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="apartment" class="text-black">Apartment</label>
                                <input type="text" class="form-control" id="apartment" name="apartment" placeholder="apartment, suite, unit etc. (optional)" value="{{ (!empty($customerAddress)) ? $customerAddress->apartment : ''}}">
                                <p></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="city" class="text-black">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ (!empty($customerAddress)) ? $customerAddress->city : ''}}">
                                <p></p>
                            </div>
                            <div class="col-md-6">
                                <label for="state" class="text-black">State <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="state" name="state" value="{{ (!empty($customerAddress)) ? $customerAddress->state : ''}}">
                                <p></p>
                            </div>
                            <div class="col-md-6">
                                <label for="zip" class="text-black">Zip <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="zip" name="zip" value="{{ (!empty($customerAddress)) ? $customerAddress->zip : ''}}">
                                <p></p>
                            </div>
                        </div>

                        <div class="form-group row mb-5">
                            <div class="col-md-6">
                                <label for="email" class="text-black">Email Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="email" name="email" value="{{ (!empty($customerAddress)) ? $customerAddress->email : ''}}">
                                <p></p>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="text-black">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number" value="{{ (!empty($customerAddress)) ? $customerAddress->phone : ''}}">
                                <p></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes" class="text-black">Order Notes</label>
                            <textarea name="notes" id="notes" cols="30" rows="5" class="form-control" placeholder="Write your notes here..."></textarea>
                        </div>

                    </div>
                    </div>
                    <div class="col-md-6">

                        <div class="row mb-5">
                            <div class="col-md-12">
                                <h2 class="h3 mb-3 text-black">Coupon Code</h2>
                                <div class="p-3 p-lg-5 border">
                                    
                                    <label class="text-black mb-3">Enter your coupon code if you have one</label>
                                    <div class="input-group w-75">
                                        <input type="text" class="form-control" name="discount_code" id="discount_code" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="button-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary btn-sm" type="button" id="apply-discount">Apply</button>
                                        </div>
                                    </div>
                                </div>
                                <div id="discount-response-wrapper">
                                    @if (Session::has('code'))
                                        <div class="input-group w-85" id="discount-response">
                                            <strong class="form-control"> {{ Session::get('code')->code }} </strong>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger btn-sm" type="button" id="remove-discount">Delete</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    
                        <div class="row mb-5">
                            <div class="col-md-12">
                                <h2 class="h3 mb-3 text-black">Your Order</h2>
                                <div class="p-3 p-lg-5 border">
                                    <table class="table site-block-order-table mb-5">
                                        <thead>
                                            <th>Product</th>
                                            <th>Total</th>
                                        </thead>
                                        <tbody>
                                            @foreach (Cart::content() as $item)
                                                <tr class="product-row">
                                                    <td>{{ $item->name }} <strong class="mx-2">x</strong> {{ $item->qty }} </td>
                                                    <td>${{ $item->price * $item->qty }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
                                                <td class="text-black">${{ Cart::Subtotal() }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-black font-weight-bold"><strong>Discount</strong></td>
                                                <td id="discount_value" class="text-black">${{ $discount }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-black font-weight-bold"><strong>Shipping</strong></td>
                                                <td class="text-black" id="shippingAmount">${{ $totalShippingCharges }}</td> {{--number_format formats result to like .00--}}
                                            </tr>
                                            <tr>
                                                <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                                                <td class="text-black font-weight-bold" id="grandTotal"><strong>${{ $grandTotal }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                        <div class="">
                                            <h3 class="card-title h5 mb-3">Payment Method</h3>
                                            <div>
                                                <input checked type="radio" name="payment_method" value="cod" id="payment_method_one">
                                                <label for="payment_method_one" class="form-check-label">COD</label>
                                            </div>

                                            <div>
                                                <input type="radio" name="payment_method" value="stripe" id="payment_method_two">
                                                <label for="payment_method_two" class="form-check-label">Stripe</label>
                                            </div>
                                        </div>
                                
                                    <div class="form-group mt-2">
                                        <button type="submit" class="btn btn-primary btn-lg py-3 btn-block" >Place Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('customJs')
    <script>
        // Payment methods rendering
        // $("#payment_method_one").click(function(){
        //     if ($(this).is(":checked") == true) {
        //         $("#stripe-payment-form").addClass('d-none');
        //     }
        // });

        // $("#payment_method_two").click(function(){
        //     if ($(this).is(":checked") == true) {
        //         $("#stripe-payment-form").removeClass('d-none');
        //     }
        // });

        // // All Forms submission
        // $("#orderForm").submit(function(event){
        //     event.preventDefault();

        //     $('button[type="submit"]').prop('disabled',true);

        //     $.ajax({
        //         url: '{{ route("front.processCheckout") }}',
        //         type: 'post',
        //         data: $(this).serializeArray(),
        //         dataType: 'json',
        //         success: function(response){
        //             // Displaying errors return in response
        //             var errors = response.errors;
        //             $('button[type="submit"]').prop('disabled',false);

        //             if (response.status == false) {
        //                 if (errors.fname) {
        //                     $("#fname").addClass('is-invalid')
        //                     .siblings("p")
        //                     .addClass('invalid-feedback')
        //                     .html(errors.fname);
        //                 } else {
        //                     $("#fname").removeClass('is-invalid')
        //                     .siblings("p")
        //                     .removeClass('invalid-feedback')
        //                     .html('');
        //                 }

        //                 if (errors.lname) {
        //                     $("#lname").addClass('is-invalid')
        //                     .siblings("p")
        //                     .addClass('invalid-feedback')
        //                     .html(errors.lname);
        //                 } else {
        //                     $("#lname").removeClass('is-invalid')
        //                     .siblings("p")
        //                     .removeClass('invalid-feedback')
        //                     .html('');
        //                 }

        //                 if (errors.country) {
        //                     $("#country").addClass('is-invalid')
        //                     .siblings("p")
        //                     .addClass('invalid-feedback')
        //                     .html(errors.country);
        //                 } else {
        //                     $("#country").removeClass('is-invalid')
        //                     .siblings("p")
        //                     .removeClass('invalid-feedback')
        //                     .html('');
        //                 }

        //                 if (errors.address) {
        //                     $("#address").addClass('is-invalid')
        //                     .siblings("p")
        //                     .addClass('invalid-feedback')
        //                     .html(errors.address);
        //                 } else {
        //                     $("#address").removeClass('is-invalid')
        //                     .siblings("p")
        //                     .removeClass('invalid-feedback')
        //                     .html('');
        //                 }

        //                 if (errors.city) {
        //                     $("#city").addClass('is-invalid')
        //                     .siblings("p")
        //                     .addClass('invalid-feedback')
        //                     .html(errors.city);
        //                 } else {
        //                     $("#city").removeClass('is-invalid')
        //                     .siblings("p")
        //                     .removeClass('invalid-feedback')
        //                     .html('');
        //                 }

        //                 if (errors.state) {
        //                     $("#state").addClass('is-invalid')
        //                     .siblings("p")
        //                     .addClass('invalid-feedback')
        //                     .html(errors.state);
        //                 } else {
        //                     $("#state").removeClass('is-invalid')
        //                     .siblings("p")
        //                     .removeClass('invalid-feedback')
        //                     .html('');
        //                 }

        //                 if (errors.zip) {
        //                     $("#zip").addClass('is-invalid')
        //                     .siblings("p")
        //                     .addClass('invalid-feedback')
        //                     .html(errors.zip);
        //                 } else {
        //                     $("#zip").removeClass('is-invalid')
        //                     .siblings("p")
        //                     .removeClass('invalid-feedback')
        //                     .html('');
        //                 }

        //                 if (errors.email) {
        //                     $("#email").addClass('is-invalid')
        //                     .siblings("p")
        //                     .addClass('invalid-feedback')
        //                     .html(errors.email);
        //                 } else {
        //                     $("#email").removeClass('is-invalid')
        //                     .siblings("p")
        //                     .removeClass('invalid-feedback')
        //                     .html('');
        //                 }
                        
        //                 if (errors.phone) {
        //                     $("#phone").addClass('is-invalid')
        //                     .siblings("p")
        //                     .addClass('invalid-feedback')
        //                     .html(errors.phone);
        //                 } else {
        //                     $("#phone").removeClass('is-invalid')
        //                     .siblings("p")
        //                     .removeClass('invalid-feedback')
        //                     .html('');
        //                 }
        //             } else {
        //                 window.location.href = "{{ url('thankyou/') }}/"+response.orderId;
        //             }

        //         }
        //     });
        // });

        var grandTotal = {{$grandTotal}};
        // var productData = [];

        // $("tbody .product-row").each(function() {
        //     var productInfo = $(this).find("td:first-child").text().trim(); // Get the text content of the first <td> within the product row
        //     var productName = productInfo.split(' x ')[0].trim(); // Extract product name before 'x'
        //     var qty = productInfo.split(' x ')[1].trim(); // Extract quantity after 'x'
            
        //     // Push product name and quantity as an object to the productData array
        //     productData.push({ name: productName, quantity: qty });
        // });

        $("#orderForm").submit(function(event){
            event.preventDefault();

            $('button[type="submit"]').prop('disabled', true);

            var paymentMethod = $('input[name="payment_method"]:checked').val();

            // If payment method is COD, proceed with order placement
            if (paymentMethod === 'cod') {
                codOrder();
            } else if (paymentMethod === 'stripe') {
                // passing grandTotal
                var grandTotalInput = $('<input>').attr({
                type: 'hidden',
                name: 'grand_total',
                value: grandTotal
                });

                // Convert productData array to JSON string and append as hidden input
                // var productDataInput = $('<input>').attr({
                //     type: 'hidden',
                //     name: 'product_data',
                //     value: JSON.stringify(productData)
                // });

                $('#orderForm').append(grandTotalInput);

                if (confirm('Are you sure you want to proceed with Stripe payment?')) {
                    stripeOrder();
                } else {
                    $('button[type="submit"]').prop('disabled', false);
                }
            }
        });

    // for cod
    function codOrder() {
        $.ajax({
            url: '{{ route("front.processCheckout") }}',
            type: 'post',
            data: $("#orderForm").serializeArray(),
            dataType: 'json',
            success: function(response){
                var errors = response.errors;
                if (response.status === false) {
                    if (errors.fname) {
                        $("#fname").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.fname);
                    } else {
                        $("#fname").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if (errors.lname) {
                        $("#lname").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.lname);
                    } else {
                        $("#lname").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if (errors.country) {
                        $("#country").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.country);
                    } else {
                        $("#country").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if (errors.address) {
                        $("#address").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.address);
                    } else {
                        $("#address").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if (errors.city) {
                        $("#city").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.city);
                    } else {
                        $("#city").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if (errors.state) {
                        $("#state").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.state);
                    } else {
                        $("#state").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if (errors.zip) {
                        $("#zip").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.zip);
                    } else {
                        $("#zip").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }

                    if (errors.email) {
                        $("#email").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.email);
                    } else {
                        $("#email").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }
                    
                    if (errors.phone) {
                        $("#phone").addClass('is-invalid')
                        .siblings("p")
                        .addClass('invalid-feedback')
                        .html(errors.phone);
                    } else {
                        $("#phone").removeClass('is-invalid')
                        .siblings("p")
                        .removeClass('invalid-feedback')
                        .html('');
                    }
                } else {
                    window.location.href = "{{ url('thankyou/') }}/"+response.orderId;
                }
            }
        });
    }

    // for stripe
    function stripeOrder() {
        $.ajax({
            url: '{{ route("stripe") }}',
            type: 'post',
            headers: {
                'Content-Type':'application/json',
                'Accept':'application/json'
            },
            
            data: $("#orderForm").serializeArray(),
            dataType: 'json',
            success: function(response){
                var errors = response.errors;
                if (response.status === false) {
                    // if (errors.fname) {
                    //     $("#fname").addClass('is-invalid')
                    //     .siblings("p")
                    //     .addClass('invalid-feedback')
                    //     .html(errors.fname);
                    // } else {
                    //     $("#fname").removeClass('is-invalid')
                    //     .siblings("p")
                    //     .removeClass('invalid-feedback')
                    //     .html('');
                    // }

                    // if (errors.lname) {
                    //     $("#lname").addClass('is-invalid')
                    //     .siblings("p")
                    //     .addClass('invalid-feedback')
                    //     .html(errors.lname);
                    // } else {
                    //     $("#lname").removeClass('is-invalid')
                    //     .siblings("p")
                    //     .removeClass('invalid-feedback')
                    //     .html('');
                    // }

                    // if (errors.country) {
                    //     $("#country").addClass('is-invalid')
                    //     .siblings("p")
                    //     .addClass('invalid-feedback')
                    //     .html(errors.country);
                    // } else {
                    //     $("#country").removeClass('is-invalid')
                    //     .siblings("p")
                    //     .removeClass('invalid-feedback')
                    //     .html('');
                    // }

                    // if (errors.address) {
                    //     $("#address").addClass('is-invalid')
                    //     .siblings("p")
                    //     .addClass('invalid-feedback')
                    //     .html(errors.address);
                    // } else {
                    //     $("#address").removeClass('is-invalid')
                    //     .siblings("p")
                    //     .removeClass('invalid-feedback')
                    //     .html('');
                    // }

                    // if (errors.city) {
                    //     $("#city").addClass('is-invalid')
                    //     .siblings("p")
                    //     .addClass('invalid-feedback')
                    //     .html(errors.city);
                    // } else {
                    //     $("#city").removeClass('is-invalid')
                    //     .siblings("p")
                    //     .removeClass('invalid-feedback')
                    //     .html('');
                    // }

                    // if (errors.state) {
                    //     $("#state").addClass('is-invalid')
                    //     .siblings("p")
                    //     .addClass('invalid-feedback')
                    //     .html(errors.state);
                    // } else {
                    //     $("#state").removeClass('is-invalid')
                    //     .siblings("p")
                    //     .removeClass('invalid-feedback')
                    //     .html('');
                    // }

                    // if (errors.zip) {
                    //     $("#zip").addClass('is-invalid')
                    //     .siblings("p")
                    //     .addClass('invalid-feedback')
                    //     .html(errors.zip);
                    // } else {
                    //     $("#zip").removeClass('is-invalid')
                    //     .siblings("p")
                    //     .removeClass('invalid-feedback')
                    //     .html('');
                    // }

                    // if (errors.email) {
                    //     $("#email").addClass('is-invalid')
                    //     .siblings("p")
                    //     .addClass('invalid-feedback')
                    //     .html(errors.email);
                    // } else {
                    //     $("#email").removeClass('is-invalid')
                    //     .siblings("p")
                    //     .removeClass('invalid-feedback')
                    //     .html('');
                    // }
                    
                    // if (errors.phone) {
                    //     $("#phone").addClass('is-invalid')
                    //     .siblings("p")
                    //     .addClass('invalid-feedback')
                    //     .html(errors.phone);
                    // } else {
                    //     $("#phone").removeClass('is-invalid')
                    //     .siblings("p")
                    //     .removeClass('invalid-feedback')
                    //     .html('');
                    // }
                } else {
                    // window.location.href = "{{ url('thankyou/') }}/"+response.orderId;
                }
            }
        });
    }


        // If User Changes Country on checkout then shipping charges should calculate
        $("#country").change(function(){
            $.ajax({
                url: '{{ route("front.getOrderSummary") }}',
                type: 'post',
                data: {country_id: $(this).val()},
                dataType: 'json',
                success: function(response){
                    if (response.status == true) {
                        $("#shippingAmount").html('$'+ response.shippingCharge);
                        $("#grandTotal").html('$'+ response.grandTotal);
                    } else {
                        
                    }
                }
            });
        });

        // Coupon code apply
        $("#apply-discount").click(function(){
            $.ajax({
                url: '{{ route("front.applyDiscount") }}',
                type: 'post',
                data: {code: $("#discount_code").val(), country_id: $("#country").val()},
                dataType: 'json',
                success: function(response){
                    if (response.status == true) {
                        $("#shippingAmount").html('$'+ response.shippingCharge);
                        $("#grandTotal").html('$' + response.grandTotal);
                        $("#discount_value").html('$' + response.discount);
                        $("#discount-response-wrapper").html(response.discountString);
                    } else {
                        $("#discount-response-wrapper").html("<span class='text-danger'>" + response.message + "</span>");
                    }
                    
                }
            });
        });
        
        // Remove coupon
        $('body').on('click',"#remove-discount",function(){
            $.ajax({
                url: '{{ route("front.removeCoupon") }}',
                type: 'post',
                data: {country_id: $("#country").val()},
                dataType: 'json',
                success: function(response){
                    if (response.status == true) {
                        $("#shippingAmount").html('$'+ response.shippingCharge);
                        $("#grandTotal").html('$' + response.grandTotal);
                        $("#discount_value").html('$' + response.discount);
                        $("#discount-response").html('');
                        $("#discount_code").html('');
                        
                    }
                    
                }
            });
        });

    </script>
@endsection