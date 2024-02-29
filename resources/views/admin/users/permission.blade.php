@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

    @section('content') {{--calling dynamic content with same name provided in parent directory--}}
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Create Permission
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
                                <form class="form-horizontal form-vcenter" action="" method="POST" id="permissionForm" name="permissionForm" enctype="multipart/form-data">
                                    <div class="mb-3 row">
                                        <label for="name" class="col-3 col-form-label required">Name</label>
                                        <div class="col">
                                            <input type="text" name="name" id="name" class="form-control" aria-describedby="nameHelp" placeholder="Enter name">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="description" class="col-3 col-form-label">Description</label>
                                        <div class="col">
                                            <textarea name="description" id="description" class="form-control" placeholder="Description"></textarea>
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
        $("#permissionForm").submit(function(event){
            event.preventDefault();

            var registerData = $("#permissionForm")[0];
            var element = new FormData(registerData)

            $.ajax({
                url: "{{ route('users.permissionStore') }}",
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
                    }
                }, 
                error: function(jqXHR, exception){
                    console.log("Something went wrong"); //jqXHR identifies the error
                }
            })
        });

    </script>
    @endsection
