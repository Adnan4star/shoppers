@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

    @section('content') {{--calling dynamic content with same name provided in parent directory--}}
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Change Password
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container">
                <div class="row row-cards">
                    <div class="col-lg-12">
                        @include('admin.message')
                        <div class="card">
                            <div class="form-responsive" style="margin-top: 20px; margin-left: 20px; margin-right: 20px;">
                                <form class="form-horizontal form-vcenter" action="" method="POST" id="change-password-submit-form" name="change-password-submit-form" enctype="multipart/form-data">
                                    <div class="mb-3 row">
                                        <label for="old_password" class="col-3 col-form-label required">Old Password</label>
                                        <div class="col">
                                            <input type="password" name="old_password" id="old_password" class="form-control" aria-describedby="nameHelp" placeholder="Old Password">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="new_password" class="col-3 col-form-label required">New Password</label>
                                        <div class="col">
                                            <input type="password" name="new_password" id="new_password" class="form-control" aria-describedby="nameHelp" placeholder="New Password">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="confirm_password" class="col-3 col-form-label required">Confirm Password</label>
                                        <div class="col">
                                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" aria-describedby="nameHelp" placeholder="Confirm Password">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        {{-- <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a> --}}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    @endsection
    
    @section('customJs')
        <script type="text/javascript">
            $("#change-password-submit-form").submit(function(e){
                e.preventDefault();

                // Disabling submit button once clicked
                $("button[type='submit']").prop('disabled',true);

                $.ajax({
                    url: '{{route("admin.process-change-Password")}}',
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

                            window.location.href = '{{ route("admin.showChangePasswordForm") }}';
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
