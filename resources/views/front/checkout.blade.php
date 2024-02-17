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
                                <option>Select a country</option>    
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

                        {{-- <div class="row mb-5">
                            <div class="col-md-12">
                            <h2 class="h3 mb-3 text-black">Coupon Code</h2>
                            <div class="p-3 p-lg-5 border">
                                
                                <label for="c_code" class="text-black mb-3">Enter your coupon code if you have one</label>
                                <div class="input-group w-75">
                                <input type="text" class="form-control" id="c_code" placeholder="Coupon Code" aria-label="Coupon Code" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-sm" type="button" id="button-addon2">Apply</button>
                                </div>
                                </div>

                            </div>
                            </div>
                        </div> --}}
                    
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
                                                <tr>
                                                    <td>{{ $item->name }} <strong class="mx-2">x</strong> {{ $item->qty }}</td>
                                                    <td>${{ $item->price * $item->qty }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
                                                <td class="text-black">${{ Cart::Subtotal() }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                                                <td class="text-black font-weight-bold"><strong>${{ Cart::Subtotal() }}</strong></td>
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
                                            <input type="radio" name="payment_method" value="cod" id="payment_method_two">
                                            <label for="payment_method_two" class="form-check-label">Direct Bank Transfer</label>
                                        </div>

                                        <div class="card-body p-0 d-none mt-3" id="card-payment-form">
                                            <div class="mb-3">
                                                <label for="card_number" class="mb-2">Card Number</label>
                                                <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="expiry_date" class="mb-2">Expiry Date</label>
                                                    <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="cvv" class="mb-2">CVV Code</label>
                                                    <input type="text" name="cvv" id="cvv" placeholder="123" class="form-control">
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg py-3 btn-block" >Place Order</button>
                                        </div>
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
        $("#payment_method_one").click(function(){
            if ($(this).is(":checked") == true) {
                $("#card-payment-form").addClass('d-none');
            }
        });

        $("#payment_method_two").click(function(){
            if ($(this).is(":checked") == true) {
                $("#card-payment-form").removeClass('d-none');
            }
        });

        // Form submission
        $("#orderForm").submit(function(event){
            event.preventDefault();

            $('button[type="submit"]').prop('disabled',true);

            $.ajax({
                url: '{{ route("front.processCheckout") }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response){
                    // Displaying errors return in response
                    var errors = response.errors;
                    $('button[type="submit"]').prop('disabled',false);

                    if (response.status == false) {
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
        });
    </script>
@endsection