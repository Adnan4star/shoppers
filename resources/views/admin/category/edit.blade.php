@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

@section('content') {{--calling dynamic content with same name provided in parent directory--}}
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Edit Category
                    </h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('categories.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container">
            <div class="row row-cards">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="form-responsive" style="margin-top: 20px; margin-left: 20px; margin-right: 20px;">
                            <form class="form-horizontal form-vcenter" action="" method="POST" id="categoryForm" name="categoryForm" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="mb-3 row">
                                        <label for="name" class="col-3 col-form-label required">Name</label>
                                        <div class="col">
                                            <input type="text" name="name" id="name" value="{{ $category->name }}" class="form-control" aria-describedby="nameHelp" placeholder="Enter name">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="slug" class="col-3 col-form-label required">Slug</label>
                                        <div class="col">
                                            <input type="text" readonly name="slug" id="slug" value="{{ $category->slug }}" class="form-control" placeholder="slug">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="image" class="col-3 col-form-label">Image</label>
                                        <div class="col-9">
                                            <img src="{{ asset('uploads/' . $category->image) }}" alt="category image" style="width: auto;height: 150px;">
                                            <input type="file" name="image" id="image" value="" class="form-control mt-2" />
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="status" class="col-3 col-form-label">Status</label>
                                        <div class="col">
                                            <select name="status" id="status" class="form-control">
                                                <option {{ ($category->status == 1) ? 'selected' : '' }} value="1">Active</option>
                                                <option {{ ($category->status == 0) ? 'selected' : '' }} value="0">Block</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="showHome" class="col-3 col-form-label">Show on Home</label>
                                        <div class="col">
                                            <select name="showHome" id="showHome" class="form-control">
                                                <option {{ ($category->showHome == 'Yes') ? 'selected' : '' }} value="Yes">Yes</option>
                                                <option {{ ($category->showHome == 'No') ? 'selected' : '' }} value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                                    </div>
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
        $("#categoryForm").submit(function(event){
            event.preventDefault();

            var registerData = $("#categoryForm")[0];
            var element = new FormData(registerData)

            $.ajax({
                url: "{{ route('categories.update', $category->id) }}",
                type: 'post',
                data: element,
                contentType: false,
                processData: false,
                success: function(response){
                    console.log(response);
                    // $("button[type=submit]").prop('disabled',false);
                    
                    if(response['status'] == true){
                        window.location.href = "{{ route('categories.index') }}";
                        
                    //     $("#name").removeClass('is-invalid')
                    //     .siblings('p')
                    //     .removeClass('invalid-feedback').html("");
                        
                    //     $("#slug").removeClass('is-invalid')
                    //     .siblings('p')
                    //     .removeClass('invalid-feedback').html("");
                        
                    // }else{
                    //     if(response['notFound'] == true){
                    //         window.location.href = "{{ route('categories.index') }}";
                    //     }
                        
                    //     var errors = response['errors'];
                    //     if(errors['name']){
                    //         $("#name").addClass('is-invalid')
                    //         .siblings('p')
                    //         .addClass('invalid-feedback').html(errors['name']);
                    //     }else{
                    //         $("#name").removeClass('is-invalid')
                    //         .siblings('p')
                    //         .removeClass('invalid-feedback').html("");
                    //     }
                        
                    //     if(errors['slug']){
                    //         $("#slug").addClass('is-invalid')
                    //         .siblings('p')
                    //         .addClass('invalid-feedback').html(errors['slug']);
                    //     }else{
                    //         $("#slug").removeClass('is-invalid')
                    //         .siblings('p')
                    //         .removeClass('invalid-feedback').html("");
                    //     }
                    }
                }, 
                // error: function(jqXHR, exception){
                //     console.log("Something went wrong"); //jqXHR identifies the error
                // }
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
        
    </script>
@endsection
