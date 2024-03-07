@extends('front.layouts.app')

@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">My Account</a></li>
                        <li class="breadcrumb-item"><strong>Change Password</strong></li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-11 ">
            <div class="container  ">
                <div class="row">
                    <div class="col-md-3">
                        @include('front.account.common.sidebar')
                    </div>
                    <div>
                        @include('admin.message')
                        <div class="mb-5">
                            <form action="" method="POST" id="change_password" name="change_password">
                                <div class="col-md-12 mt-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="h5 mb-0 pt-2 pb-2"><strong>Change Password</strong></h2>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-1">               
                                                        <label for="name">Old Password</label>
                                                        <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
                                                        <p></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-1">               
                                                        <label for="name">New Password</label>
                                                        <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                                                        <p></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-1">               
                                                        <label for="name">Confirm Password</label>
                                                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm New Password" class="form-control">
                                                        <p></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 mb-2">
                                            <button type="submit" id="submit" class="btn btn-primary btn-lg btn-block">Update</button>
                                        </div>
                                    </div>
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
    <script type="text/javascript">
        $("#change_password").submit(function(e){
            e.preventDefault();

            // Disabling submit button once clicked
            $("button[type='submit']").prop('disabled',true);

            $.ajax({
                url: '{{route("account.process-change-Password")}}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response){
                    // Disabling submit button once clicked
                    $("button[type='submit']").prop('disabled',false);

                    if (response.status == true){
                        $("#old_password").siblings("p").removeClass('invalid-feedback').html('');
                        $("#old_password").removeClass('is-invalid');

                        $("#new_password").siblings("p").removeClass('invalid-feedback').html('');
                        $("#new_password").removeClass('is-invalid');

                        $("#confirm_password").siblings("p").removeClass('invalid-feedback').html('');
                        $("#confirm_password").removeClass('is-invalid');

                        window.location.href = '{{ route("account.show-Change-Password-Form") }}';
                    } else {

                        var errors = response.errors;
                        if (errors.old_password) {
                            $("#old_password").siblings("p").addClass('invalid-feedback').html(errors.old_password); //displaying error on old_password field p tag
                            $("#old_password").addClass('is-invalid'); // adding red colour border
                        } else {
                            $("#old_password").siblings("p").removeClass('invalid-feedback').html('');
                            $("#old_password").removeClass('is-invalid');
                        }

                        if (errors.new_password) {
                            $("#new_password").siblings("p").addClass('invalid-feedback').html(errors.new_password); //displaying error on new_password field p tag
                            $("#new_password").addClass('is-invalid'); // adding red colour border
                        } else {
                            $("#new_password").siblings("p").removeClass('invalid-feedback').html('');
                            $("#new_password").removeClass('is-invalid');
                        }

                        if (errors.confirm_password) {
                            $("#confirm_password").siblings("p").addClass('invalid-feedback').html(errors.confirm_password); //displaying error on confirm_password field p tag
                            $("#confirm_password").addClass('is-invalid'); // adding red colour border
                        } else {
                            $("#confirm_password").siblings("p").removeClass('invalid-feedback').html('');
                            $("#confirm_password").removeClass('is-invalid');
                        }
                    }
                } 
            });
        });
    </script>
@endsection