@extends('front.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">My Account</a></li>
                    <li class="breadcrumb-item"><strong>My Profile</strong></li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            @include('admin.message')
            <div class="row">
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9 mb-2">
                    <div class="card" style="margin-top: 20px">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2"><strong>Personal Information</strong></h2>
                        </div>
                        <form action="" method="POST" id="profileForm" name="profileForm">
                            <div class="row">
                                <div class="col-md-12 mb-5 mb-md-0">
                                    <div class="p-3 p-lg-5 border">
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="name" class="text-black">Name <span class="text-danger">*</span></label>
                                                <input value="{{ $user->name }}" type="text" class="form-control" id="name" name="name">
                                                <p></p>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="phone" class="text-black">Phone <span class="text-danger">*</span></label>
                                                <input value="{{ $user->phone }}" type="text" class="form-control" id="phone" name="phone">
                                                <p></p>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="email" class="text-black">Email <span class="text-danger">*</span></label>
                                                <input value="{{ $user->email }}" type="text" class="form-control" id="email" name="email">
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-2 mb-2">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" value="update">Update</button>
                            </div>
                        </form>
                    </div>
                    <div class="card" style="margin-top: 20px">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2"><strong>Address Information</strong></h2>
                        </div>
                        <form action="" method="POST" id="addressForm" name="addressForm">
                            <div class="row">
                                <div class="col-md-12 mb-5 mb-md-0">
                                    <div class="p-3 p-lg-5 border">
                                        <div class="form-group">
                                            <label for="country_id" class="text-black">Country <span class="text-danger">*</span></label>
                                            <select id="country_id" name="country_id" class="form-control">
                                                <option 
                                                    value="">Select a country
                                                </option>    
                                                @if ($countries->isNotEmpty())
                                                    @foreach ($countries as $country)
                                                        <option 
                                                            {{ (!empty($customerAddress) && $customerAddress->country_id == $country->id) ? 'selected' : ''}} value="{{ $country->id }}">{{ $country->name }}
                                                        </option>    
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="first_name" class="text-black">First Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ (!empty($customerAddress)) ? $customerAddress->first_name : ''}}">
                                                <p></p>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="last_name" class="text-black">Last Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ (!empty($customerAddress)) ? $customerAddress->last_name : ''}}">
                                                <p></p>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="email" class="text-black">Email <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="email" name="email" value="{{ (!empty($customerAddress)) ? $customerAddress->email : ''}}">
                                                <p></p>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="phone" class="text-black">Phone <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="phone" name="phone" value="{{ (!empty($customerAddress)) ? $customerAddress->phone : ''}}">
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
                                            <div class="col-md-6">
                                                <label for="apartment" class="text-black">Apartment</label>
                                                <input type="text" class="form-control" id="apartment" name="apartment" placeholder="apartment, suite, unit etc. (optional)" value="{{ (!empty($customerAddress)) ? $customerAddress->apartment : ''}}">
                                                <p></p>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="city" class="text-black">City <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="city" name="city" value="{{ (!empty($customerAddress)) ? $customerAddress->city : ''}}">
                                                <p></p>
                                            </div>
                                        </div>
                
                                        <div class="form-group row">
                                            
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
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <button type="submit" class="btn btn-primary btn-lg btn-block mb-2" value="UpdateAddress">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('customJs')
    <script>
        // Profile form update
        $("#profileForm").submit(function(event){
            event.preventDefault();

            // Disabling submit button once clicked
            $("button[type='submit']").prop('disabled',true);

            $.ajax({
                url: '{{ route("account.updateProfile") }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response){
                    // Disabling submit button once clicked
                    $("button[type='submit']").prop('disabled',false);

                    if (response.status == true){
                        window.location.href = '{{ route("account.profile") }}';

                        $("#profileForm #name").siblings("p").removeClass('invalid-feedback').html('');
                        $("#name").removeClass('is-invalid');

                        $("#profileForm #email").siblings("p").removeClass('invalid-feedback').html('');
                        $("#profileForm #email").removeClass('is-invalid');

                        $("#profileForm #phone").siblings("p").removeClass('invalid-feedback').html('');
                        $("#profileForm #phone").removeClass('is-invalid');
                    } else {
                        var errors = response.errors;
                        if (errors.name) {
                            $("#profileForm #name").siblings("p").addClass('invalid-feedback').html(errors.name); //displaying error on name field p tag
                            $("#profileForm #name").addClass('is-invalid'); // adding red colour border
                        } else {
                            $("#profileForm #name").siblings("p").removeClass('invalid-feedback').html('');
                            $("#profileForm #name").removeClass('is-invalid');
                        }

                        if (errors.email) {
                            $("#profileForm #email").siblings("p").addClass('invalid-feedback').html(errors.email); //displaying error on email field p tag
                            $("#profileForm #email").addClass('is-invalid'); // adding red colour border
                        } else {
                            $("#profileForm #email").siblings("p").removeClass('invalid-feedback').html('');
                            $("#profileForm #email").removeClass('is-invalid');
                        }

                        if (errors.phone) {
                            $("#profileForm #phone").siblings("p").addClass('invalid-feedback').html(errors.phone); //displaying error on phone field p tag
                            $("#profileForm #phone").addClass('is-invalid'); // adding red colour border
                        } else {
                            $("#profileForm #phone").siblings("p").removeClass('invalid-feedback').html('');
                            $("#profileForm #phone").removeClass('is-invalid');
                        }
                    }
                }
            });
        });

        // Address form update 
        $("#addressForm").submit(function(event){
            event.preventDefault();

            $('button[type="submit"]').prop('disabled',true);

            $.ajax({
                url: '{{ route("account.updateAddress") }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response){
                    // Displaying errors return in response
                    var errors = response.errors;
                    $('button[type="submit"]').prop('disabled',false);

                    if (response.status == false) {
                        if (errors.first_name) {
                            $("#first_name").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feedback')
                            .html(errors.first_name);
                        } else {
                            $("#first_name").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feedback')
                            .html('');
                        }

                        if (errors.last_name) {
                            $("#last_name").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feedback')
                            .html(errors.last_name);
                        } else {
                            $("#last_name").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feedback')
                            .html('');
                        }

                        if (errors.country_id) {
                            $("#country_id").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feedback')
                            .html(errors.country_id);
                        } else {
                            $("#country_id").removeClass('is-invalid')
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
                            $("#addressForm #email").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feedback')
                            .html(errors.email);
                        } else {
                            $("#addressForm #email").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feedback')
                            .html('');
                        }
                        
                        if (errors.phone) {
                            $("#addressForm #phone").addClass('is-invalid')
                            .siblings("p")
                            .addClass('invalid-feedback')
                            .html(errors.phone);
                        } else {
                            $("#addressForm #phone").removeClass('is-invalid')
                            .siblings("p")
                            .removeClass('invalid-feedback')
                            .html('');
                        }

                    } else {
                        window.location.href = '{{ route("account.profile") }}';
                    }

                }
            });
        });
    </script>
@endsection