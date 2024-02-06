@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

@section('content') {{--calling dynamic content with same name provided in parent directory--}}
<div class="col-md-6">
    <form class="card" action="" method="POST" id="subCategoryForm" name="subCategoryForm">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h3 class="card-title">Create Sub Category</h3>
            </div>
            <div class="col-sm-2 text-right">
                <a href="{{ route('sub-categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3 row">
                <label for="category" class="col-3 col-form-label">Category</label>
                <div class="col">
                    <select name="category" id="category" class="form-control">
                        <option value="">Select a Category</option>
                        @if($categories->isNotEmpty())
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                        @endif
                    </select>
                    <p></p>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="name" class="col-3 col-form-label required">Name</label>
                <div class="col">
                    <input type="text" name="name" id="name" class="form-control" aria-describedby="nameHelp" placeholder="Enter name">
                    <p></p>
                    <small class="form-hint">Please enter name for your sub-category.</small>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="slug" class="col-3 col-form-label required">Slug</label>
                <div class="col">
                    <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="slug">
                    <p></p>
                    <small class="form-hint">
                        Please enter slug for your sub-category.
                    </small>
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
            <div class="mb-3 row">
                <label class="col-3 col-form-label">Show on Home</label>
                <div class="col">
                    <select name="showHome" id="showHome" class="form-control">
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{route('sub-categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    @endsection
    
@section('customJs')
<script>
    $("#subCategoryForm").submit(function(event){
        event.preventDefault();

        var element = $("#subCategoryForm");
        $("button[type=submit]").prop('disabled',true);

        $.ajax({
            url: "{{ route('sub-categories.store') }}",
            type: 'post',
            data: element.serializeArray(), //serialize array will get the form enteries and pass on to ajax
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled',false);

                if(response['status'] == true){
                    window.location.href = "{{ route('sub-categories.index') }}";
                    
                    $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                    
                    $("#slug").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                    
                }else{
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
