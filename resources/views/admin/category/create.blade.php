@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

@section('content') {{--calling dynamic content with same name provided in parent directory--}}
<div class="col-md-6">
    <form class="card" action="" method="POST" id="categoryForm" name="categoryForm">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h3 class="card-title">Create Category</h3>
            </div>
            <div class="col-sm-2 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3 row">
                <label for="name" class="col-3 col-form-label required">Name</label>
                <div class="col">
                    <input type="text" name="name" id="name" class="form-control" aria-describedby="nameHelp" placeholder="Enter name">
                    <p></p>
                    <small class="form-hint">Name is a required field to submit category.</small>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="slug" class="col-3 col-form-label required">Slug</label>
                <div class="col">
                    <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="slug">
                    <p></p>
                    <small class="form-hint">
                        Please enter slug for you category.
                    </small>
                </div>
            </div>
            <div class="mb-3 row">
                <input type="hidden" name="image_id" id="image_id" value="">
                <label for="image" class="col-3 col-form-Image">Image</label>
                <div class="col">
                    <div id="image" class="dropzone dz-clickable">
                        <div class="dz-message needsclick">    
                            <br>Drop files here or click to upload.<br><br>                                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="status" class="col-3 col-form-label">Status</label>
                <div class="col">
                    <select name="status" id="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Block</option>
                    </select>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    @endsection
    
    @section('customJs')
    <script> //validating form through ajax
        $("#categoryForm").submit(function(event){
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled',true);
            $.ajax({
                url: "{{ route('categories.store') }}",
                type: 'post',
                data: element.serializeArray(), //serialize array will get the form enteries and pass on to ajax
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled',false);

                    if(response['status'] == true){
                        window.location.href = "{{ route('categories.index') }}";
                        
                        $("#name").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                        
                        $("#slug").removeClass('is-invalid')
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
                        
                        if(errors['slug']){
                            $("#slug").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['slug']);
                        }else{
                            $("#slug").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }
                    }
                }, error: function(jqXHR, exception){
                    console.log("Something went wrong"); //jqXHR identifies the error
                }
            })
        });
        //getting slug set in routes
        $("#name").change(function(){
            $("button[type=submit]").prop('disabled',true);

            element = $(this);
            $.ajax({
                url: "{{ route('getSlug') }}",
                type: 'get',
                data: {title: element.val()}, //getting title which is filled in input field
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled',false);

                    if(response["status"] == true){
                        $("#slug").val(response["slug"]);
                    }
                }
            });
        });
       
        //Drop zone script for image upload in category module 
    document.addEventListener("DOMContentLoaded", function() {
        Dropzone.autoDiscover = false;    
        const dropzone = $("#image").dropzone({ 
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url:  "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function(file, response){
                $("#image_id").val(response.image_id);
                //console.log(response)
            }
        });
    });
    </script>
    @endsection
