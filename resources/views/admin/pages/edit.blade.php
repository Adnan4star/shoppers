@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

@section('content') {{--calling dynamic content with same name provided in parent directory--}}
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Edit Page
                    </h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('pages.index') }}" class="btn btn-primary">Back</a>
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
                            <form class="form-horizontal form-vcenter" action="" method="POST" id="updatePage" name="updatePage">
                                <div class="card-body">
                                    <div class="mb-3 row">
                                        <label for="name" class="col-3 col-form-label required">Name</label>
                                        <div class="col">
                                            <input value="{{$pages->name}}" type="text" name="name" id="name" class="form-control" aria-describedby="nameHelp" placeholder="Enter name">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="slug" class="col-3 col-form-label required">Slug</label>
                                        <div class="col">
                                            <input value="{{$pages->slug}}" type="text" readonly name="slug" id="slug" class="form-control" placeholder="slug">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="content" class="col-3 col-form-label ">Content</label>
                                        <div class="col">
                                            <textarea type="text" name="content" id="content" class="form-control" placeholder="Content goes here..">{{$pages->content}} </textarea>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="{{ route('pages.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
        $("#updatePage").submit(function(event){
            event.preventDefault();

            var element = $("#updatePage");
            $("button[type=submit]").prop('disabled',true);

            $.ajax({
                url: "{{  route('pages.update', $pages->id) }}",
                type: 'post',
                data: element.serializeArray(), //serialize array will get the form enteries and pass on to ajax
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled',false);

                    if(response['status'] == true){
                        window.location.href = "{{ route('pages.index') }}";
                        
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

    </script>
    @endsection
