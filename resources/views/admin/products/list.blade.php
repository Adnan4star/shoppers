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
            <div class="col-auto">
                <a href="{{ route('products.create') }}" class="btn btn-primary">New Product</a>
            </div>
            <div class="my-2 my-md-2 flex-grow-1 flex-md-grow-0 order-first order-md-last col-lg-12">
                <form action="" method="get" autocomplete="off" novalidate>
                    {{-- <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route('categories.index') }}'" class="btn btn-success btn-sm form-control">Reset</button>
                    </div> --}}
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                            <svg xmlns="{{ asset('admin-assets/http://www.w3.org/2000/svg') }}" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                        </span>
                        <input type="text" name="keyword" value="{{ Request::get('keyword') }}" class="form-control" placeholder="Search…" aria-label="Search in website">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-lg-12">
                @include('admin.message')
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th width="10%">ID</th>
                                    <th width="10%"></th>
                                    <th width="15%">Product</th>
                                    <th width="15%">Price</th>
                                    <th width="10%">Qty</th>
                                    <th width="10%">SKU</th>
                                    <th width="10%">Status</th>
                                    <th width="30%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($products->isNotEmpty())
                                    @foreach ($products as $product)

                                    {{-- @php
                                        $productImage = $product->product_images->first(); //calling product_image table defined relation with products table of product model   
                                    @endphp --}}
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        @if(!empty($product->image))
                                            <img src="{{ asset('uploads/products/'.$product->image) }}" class="img-thumbnail" width="50" >
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
                                        <a href="#" onclick="deleteProduct({{ $product->id }})" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">Records Not Found</td>
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
