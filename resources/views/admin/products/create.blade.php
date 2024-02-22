@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

@section('content') {{--calling dynamic content with same name provided in parent directory--}}
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Create Products
                    </h2>
                </div>
                <div class="col-auto text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Back</a>
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
                            <form class="form-horizontal form-vcenter" action="" method="POST" id="productForm" name="productForm">
                                <div class="card-body">
                                        <div class="mb-3 row">
                                            <label for="title" class="col-3 col-form-label required">Title</label>
                                            <div class="col">
                                                <input type="text" name="title" id="title" class="form-control" aria-describedby="titleHelp" placeholder="Enter title">
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="slug" class="col-3 col-form-label required">Slug</label>
                                            <div class="col">
                                                <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="slug">
                                                <p class="error"></p>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="description" class="col-3 col-form-label">Description</label>
                                            <div class="col">
                                                <textarea name="description" id="description" class="form-control" placeholder="Description"></textarea>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="image" class="col-3 col-form-label">Image</label>
                                            <div class="col">
                                                <input type="file" name="image" id="image" value="" class="form-control"/>
                                            </div>
                                        </div>
                                        
                                    
                                    <div class="mb-3 row">
                                        <label for="pricing" class="col-3 col-form-label required">Pricing</label>
                                        <div class="col">
                                            <input type="text" name="pricing" id="pricing" class="form-control" placeholder="Pricing">
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="compare_price" class="col-3 col-form-label">Compare Price</label>
                                        <div class="col">
                                            <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                            </p>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <h2 class="col-3 col-form-label required">Inventory</h2>								
                                        <div class="col">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sku">SKU (Stock Keeping Unit)</label>
                                                    <input type="text" name="sku" id="sku" class="form-control" placeholder="sku">
                                                    <p class="error"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="barcode">Barcode</label>
                                                    <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode">	
                                                </div>
                                            </div>   
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="hidden" name="track_qty" value="No">
                                                        <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" checked>
                                                        <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                        <p class="error"></p>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty">	
                                                    <p class="error"></p>
                                                </div>
                                            </div>                                         
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <h2 class="col-3 col-form-label">Product status</h2>
                                        <div class="col">
                                            <select name="status" id="status" class="form-control">
                                                <option value="1">Active</option>
                                                <option value="0">Block</option>
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="mb-3 row">	
                                        <h2 class="col-3 col-form-label required">Product category</h2>
                                        <div class="col">
                                            <select name="category" id="category" class="form-control">
                                                <option value="">Select a Category</option>
                                                
                                                @if($categories->isNotEmpty())
                                                @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                                @endif
                                                
                                            </select>
                                            <p class="error"></p>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="sub_category" class="col-3 col-form-label">Sub category</label>
                                            <div class="col">
                                                <select name="sub_category" id="sub_category" class="form-control">
                                                    <option value="">Select a SubCategory</option>{{--Visit "ProductSubCategoryController" to see working based on category selection, subcategory will select automatically--}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">	
                                        <h2 class="col-3 col-form-label">Product brand</h2>
                                        <div class="col">
                                            <select name="brand" id="brand" class="form-control">
                                                <option value="">Select a Brand</option>
                                                
                                                @if($brands->isNotEmpty())
                                                @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                                @endif
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">	
                                        <h2 class="col-3 col-form-label required">Featured product</h2>
                                        <div class="col">
                                            <select name="is_featured" id="is_featured" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>                                                
                                            </select>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button type="submit" class="btn btn-primary">Create</button>
                                        <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
        //getting slug set in routes
        $("#title").change(function(){
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

        //validating form through ajax
        $("#productForm").submit(function(event){
            event.preventDefault();

            var registerData = $("#productForm")[0];
                var element = new FormData(registerData)

                $.ajax({
                    url: "{{ route('products.store') }}",
                    type: 'post',
                    data: element,
                    contentType: false,
                    processData: false,
                    success: function(response){
                    console.log(response);
                    
                    if(response['status'] == true){
                        $(".error").removeClass('invalid-feedback').html(''); //removing error class from eveery field
                        $("input[type='text'], select,input[type='number']").removeClass('is-invalid'); //text and select elements removing invalid class
                        
                        window.location.href = "{{ route('products.index') }}";
                        
                    } else { 
                        var errors = response['errors'];
                        $(".error").removeClass('invalid-feedback').html(''); //removing error class from eveery field
                        $("input[type='text'], select,input[type='number']").removeClass('is-invalid'); //text and select elements removing invalid class
                        
                        $.each(errors, function(key,value){ //setting dynamic errors to all of the fields 
                            $(`#${key}`).addClass('is-invalid') //key reprsents title of row
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(value) //value represents the errors in row
                        });  
                    }
                },
                error:function(){
                    console.log("Something went wrong");
                }
                
            });
        })

        //populating sub category section on selecting category
        $("#category").change(function(){
            var category_id = $(this).val();
            
            $.ajax({
                url: '{{route("product-subcategories.index")}}',
                type: 'get',
                data: {category_id: category_id},
                dataType: 'json',
                success:function(response){
                    // console.log(response);
                    $("#sub_category").find("option").not(":first").remove();
                    $.each(response["subCategories"],function(key,item){
                        $("#sub_category").append(`<option value='${item.id}'>${item.name}</option>`)
                    });
                },
                error:function(){
                    console.log("Something went wrong");
                }
            });
        })
    </script>
@endsection
