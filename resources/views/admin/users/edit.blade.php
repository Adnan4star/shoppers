@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

    @section('content') {{--calling dynamic content with same name provided in parent directory--}}
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Edit User
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
                                <form class="form-horizontal form-vcenter" action="" method="POST" id="usersUpdate" name="usersUpdate" enctype="multipart/form-data">
                                    <div class="mb-3 row">
                                        <label for="name" class="col-3 col-form-label required">Name</label>
                                        <div class="col">
                                            <input value="{{$user->name}}"   type="text" name="name" id="name" class="form-control" aria-describedby="nameHelp" placeholder="Enter name">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="email" class="col-3 col-form-label required">Email</label>
                                        <div class="col">
                                            <input value="{{$user->email}}" type="text" name="email" id="email" class="form-control" placeholder="Email">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="phone" class="col-3 col-form-label">Phone</label>
                                        <div class="col">
                                            <input value="{{$user->phone}}"  type="text" name="phone" id="phone" class="form-control" aria-describedby="nameHelp" placeholder="Enter phone">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="password" class="col-3 col-form-label">Password</label>
                                        <div class="col">
                                            <input type="password" name="password" id="password" class="form-control" aria-describedby="nameHelp" placeholder="Enter password">
                                            <span>Enter to change password, otherwise leave blank.</span>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="roles" class="col-3 col-form-label required">Roles</label>
                                        <div class="col">
                                            <select id="roles" class="form-control" multiple name="roles[]">
                                                @foreach ($roles as $role)
                                                    <option  value="{{$role->id}}" @if(in_array($role->id, $user->roles->pluck('id')->toArray())) selected @endif> {{$role->name}} </option>
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
                                                    <option  value="{{$permission->id}}"   > {{$permission->name}} </option>
                                                @endforeach
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="status" class="col-3 col-form-label required">Status</label>
                                        <div class="col">
                                            <select name="status" id="status" class="form-control">
                                                <option {{ ($user->status == 1) ? 'selected' : '' }} value="1">Active</option>
                                                <option {{ ($user->status == 0) ? 'selected' : '' }} value="0">Block</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button type="submit" class="btn btn-primary">Update</button>
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
        $("#usersUpdate").submit(function(event){
            event.preventDefault();

            var registerData = $("#usersUpdate")[0];
            var element = new FormData(registerData)

            $.ajax({
                url: "{{ route('users.update',$user->id) }}",
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

                    }
                }, 
                error: function(jqXHR, exception){
                    console.log("Something went wrong"); //jqXHR identifies the error
                }
            })
        });

    </script>
    @endsection
