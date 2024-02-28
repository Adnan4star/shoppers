@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

    @section('content') {{--calling dynamic content with same name provided in parent directory--}}
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Create User
                        </h2>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
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
                                <form class="form-horizontal form-vcenter" action="" method="POST" id="usersForm" name="usersForm" enctype="multipart/form-data">
                                    <div class="mb-3 row">
                                        <label for="name" class="col-3 col-form-label required">Name</label>
                                        <div class="col">
                                            <input type="text" name="name" id="name" class="form-control" aria-describedby="nameHelp" placeholder="Enter name">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="email" class="col-3 col-form-label required">Email</label>
                                        <div class="col">
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="phone" class="col-3 col-form-label">Phone</label>
                                        <div class="col">
                                            <input type="text" name="phone" id="phone" class="form-control" aria-describedby="nameHelp" placeholder="Enter phone">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="password" class="col-3 col-form-label required">Password</label>
                                        <div class="col">
                                            <input type="password" name="password" id="password" class="form-control" aria-describedby="nameHelp" placeholder="Enter password">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="password_confirmation" class="col-3 col-form-label required">Confirm password</label>
                                        <div class="col">
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" aria-describedby="nameHelp" placeholder="Confirm password">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="roles" class="col-3 col-form-label required">Roles</label>
                                        <div class="col">
                                            <select id="roles" class="form-control" multiple name="roles[]">
                                                @foreach ($roles as $role)
                                                    <option value="{{$role->id}}"> {{$role->name}} </option>
                                                @endforeach
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="permissions" class="col-3 col-form-label required">Permissions</label>
                                        <div class="col">
                                            <select id="permissions" class="form-control" multiple name="permissions[]">
                                                @foreach ($permissions as $permission)
                                                    <option value="{{$permission->id}}"> {{$permission->name}} </option>
                                                @endforeach
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="status" class="col-3 col-form-label required">Status</label>
                                        <div class="col">
                                            <select name="status" id="status" class="form-control">
                                                <option value="1">Active</option>
                                                <option value="0">Block</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button type="submit" class="btn btn-primary">Create</button>
                                        <a href="{{ route('users.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
    <script> //validating form through ajax
        $("#usersForm").submit(function(event){
            event.preventDefault();

            var registerData = $("#usersForm")[0];
            var element = new FormData(registerData)

            $.ajax({
                url: "{{ route('users.store') }}",
                type: 'post',
                data: element,
                contentType: false,
                processData: false,
                success: function(response){
                    console.log(response);

                    if(response['status'] == true){
                        window.location.href = "{{ route('users.index') }}";
                        
                        $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                        
                        $("#email").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        $("#roles").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        $("#permissions").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        $("#password").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                        
                    }else{
                        var errors = response['errors'];
                        if(errors['name']){
                            $("#name").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['name']);
                        }else{
                            $("#name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }
                        
                        if(errors['email']){
                            $("#email").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['email']);
                        }else{
                            $("#email").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                        if(errors['roles']){
                            $("#roles").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['roles']);
                        }else{
                            $("#roles").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        } 

                        if(errors['permissions']){
                            $("#permissions").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['permissions']);
                        }else{
                            $("#permissions").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                        if(errors['password']){
                            $("#password").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['password']);
                        }else{
                            $("#password").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }
                    }
                }, 
                error: function(jqXHR, exception){
                    console.log("Something went wrong"); //jqXHR identifies the error
                }
            })
        });

    </script>
    @endsection
