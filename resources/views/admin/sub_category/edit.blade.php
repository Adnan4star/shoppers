@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

@section('content') {{--calling dynamic content with same name provided in parent directory--}}
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Edit Sub-Category
                    </h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('sub-categories.index') }}" class="btn btn-primary">Back</a>
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
                            <form class="form-horizontal form-vcenter" action="" method="POST" id="subCategoryForm" name="subCategoryForm">
                                <div class="card-body">
                                    <div class="mb-3 row">
                                        <label for="category" class="col-3 col-form-label">Category</label>
                                        <div class="col">
                                            <select name="category" id="category" class="form-control">
                                                <option value="">Select a Category</option>
                                                @if($categories->isNotEmpty())
                                                @foreach ($categories as $category)
                                                    <option {{ ($subCategory->category_id == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="name" class="col-3 col-form-label required">Name</label>
                                        <div class="col">
                                            <input type="text" name="name" id="name" value="{{ $subCategory->name }}" class="form-control" aria-describedby="nameHelp" placeholder="Enter name">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="slug" class="col-3 col-form-label required">Slug</label>
                                        <div class="col">
                                            <input type="text" name="slug" id="slug" value="{{ $subCategory->slug }}" class="form-control" placeholder="slug">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="status" class="col-3 col-form-label">Status</label>
                                        <div class="col">
                                            <select name="status" id="status" class="form-control">
                                                <option {{ ($subCategory->status == 1) ? 'selected' : '' }}  value="1">Active</option>
                                                <option {{ ($subCategory->status == 0) ? 'selected' : '' }} value="0">Block</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-3 col-form-label">Show on Home</label>
                                        <div class="col">
                                            <select name="showHome" id="showHome" class="form-control">
                                                <option {{ ($subCategory->showHome == 'Yes') ? 'selected' : '' }} value="Yes">Yes</option>
                                                <option {{ ($subCategory->showHome == 'No') ? 'selected' : '' }} value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="{{route('sub-categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
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
<script>
    $("#subCategoryForm").submit(function(event){
        event.preventDefault();

        var element = $("#subCategoryForm");
        $("button[type=submit]").prop('disabled',true);

        $.ajax({
            url: "{{ route('sub-categories.update',$subCategory->id) }}",
            type: 'put',
            data: element.serializeArray(), //serialize array will get the form enteries and pass on to ajax
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled',false);

                if(response['status'] == true){
                    window.location.href = "{{ route('sub-categories.index') }}";
                    
                    $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");

                    $("#category").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                    
                    $("#slug").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                    
                }else{

                    if(response['notFound'] == true){
                        window.location.href = "{{ route('sub-categories.index') }}";
                        return false;
                    }

                    var errors = response['errors'];

                    if(errors['category']){
                        $("#category").addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback').html(errors['category']);
                    }else{
                        $("#category").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                    }
                    
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
            },  error: function(jqXHR, exception){
                    console.log("Something went wrong"); //jqXHR identifies the error
                }
        })
    });

    //Getting slug for name
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
