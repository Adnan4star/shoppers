@extends('front.layouts.app')

@section('content')
<div class="site-section">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-12">
                <h2 class="h3 mb-3 text-black d-flex justify-content-center align-items-center" style="color: #7971ea;">Register Now</h2>
            </div>
            <div class="col-md-7">
                <form action="#" method="post" name="registrationForm" id="registrationForm">
                    <div class="p-3 p-lg-5 border">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="name" class="text-black">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                                <p></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="email" class="text-black">Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                                <p></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="phone" class="text-black">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="password" class="text-black">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                <p></p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label  class="text-black">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" value="register">Register</button>
                        </div>
                    </div>
                </form>
                <div class="text-center">Already have an account? <a href="{{ route('account.login') }}">Login Now</a></a></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')
    <script type="text/javascript">
        $("#registrationForm").submit(function(event){
            event.preventDefault();

            // Disabling submit button once clicked
            $("button[type='submit']").prop('disabled',true);

            $.ajax({
                url: '{{ route("account.processRegister") }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response){
                    // Enabling submit button after receiving response
                    $("button[type='submit']").prop('disabled',false);

                    // Display errors in p tag which received in var response
                    var errors = response.errors;

                    if (response.status == false) { // showing errors on fields if status = false
                        if (errors.name) {
                        $("#name").siblings("p").addClass('invalid-feedback').html(errors.name); //displaying error on name field p tag
                        $("#name").addClass('is-invalid'); // adding red colour border
                        } else {
                            $("#name").siblings("p").removeClass('invalid-feedback').html('');
                            $("#name").removeClass('is-invalid');
                        }

                        if (errors.email) {
                            $("#email").siblings("p").addClass('invalid-feedback').html(errors.email); //displaying error on email field p tag
                            $("#email").addClass('is-invalid'); // adding red colour border
                        } else {
                            $("#email").siblings("p").removeClass('invalid-feedback').html('');
                            $("#email").removeClass('is-invalid');
                        }

                        if (errors.password) {
                            $("#password").siblings("p").addClass('invalid-feedback').html(errors.password); //displaying error on password field p tag
                            $("#password").addClass('is-invalid'); // adding red colour border
                        } else {
                            $("#password").siblings("p").removeClass('invalid-feedback').html('');
                            $("#password").removeClass('is-invalid');
                        }
                    } else { // removing errors from fields if status = true
                        $("#name").siblings("p").removeClass('invalid-feedback').html('');
                        $("#name").removeClass('is-invalid');

                        $("#email").siblings("p").removeClass('invalid-feedback').html('');
                        $("#email").removeClass('is-invalid');

                        $("#password").siblings("p").removeClass('invalid-feedback').html('');
                        $("#password").removeClass('is-invalid');
                    
                        window.location.href = "{{ route('account.login') }}";
                    }
                },
                error: function(jQXHR, exception){
                    console.log("something went wrong");
                }
            });
        });
    </script>
@endsection