@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

@section('content') {{--calling dynamic content with same name provided in parent directory--}}
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Products 
                </h2>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('products.create') }}" class="btn btn-primary">New Product</a>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-lg-8">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th width="60">ID</th>
                                    <th width="80"></th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>SKU</th>
                                    <th width="100">Status</th>
                                    <th width="100">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($products->isNotEmpty())
                                    @foreach ($products as $product)

                                    @php
                                        $productImage = $product->product_images->first(); //calling product_image table defined relation with products table of product model   
                                    @endphp
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        @if(!empty($productImage->image))
                                            <img src="{{ asset('uploads/category/'.$productImage->image) }}" class="img-thumbnail" width="50" >
                                        @endif
                                    </td>
                                    <td><a href="#">{{ $product->title }}</a></td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->qty }} left in Stock</td>
                                    <td>{{ $product->sku }}</td>
                                    <td class="text-muted">
                                        {{ $product->status }}
                                    </td>
                                    <td>
                                        <a href="{{route('products.edit',$product->id)}}" class="btn btn-primary">Edit</a>
                                    </td>
                                    <td>
                                        <a href="#" onclick="deleteProduct({{ $product->id }})" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td>Records Not Found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')
    <script>
        function deleteProduct(id)
        {
            var url = '{{ route("products.delete","ID") }}';
            var newUrl = url.replace("ID",id);
            newUrl = url.replace("ID",id);
            
            if(confirm("Are you sure you want to delete?")){
                $.ajax({
                    url: newUrl,
                    type: 'delete',
                    data: {}, //serialize array will get the form enteries and pass on to ajax
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        // if(response['status']){
                            window.location.href = "{{ route('products.index') }}";
                            // location.reload();
                        // }
                    }
                });
            }
        }
    </script>
@endsection
