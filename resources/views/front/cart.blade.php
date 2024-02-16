@extends('front.layouts.app')

@section('content')
    <div class="bg-light py-3">
        <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0"><a href="{{ route('front.home') }}">Home</a> <span class="mx-2 mb-0">/</span> <a href="{{ route('front.shop') }}">Shop</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Cart</strong></div>
        </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row mb-5">
                @if(Session::has('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('success') }}
                            {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
                        </div>
                    </div>
                @endif
                @if(Session::has('error'))
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ Session::get('error') }}
                            {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
                        </div>
                    </div>
                @endif
                @if (Cart::count() > 0)
                    <form class="col-md-12" method="post">
                        <div class="site-blocks-table">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                    <th class="product-thumbnail">Image</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-total">Total</th>
                                    <th class="product-remove">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartContents as $items)
                                        <tr>
                                            <td class="product-thumbnail">
                                                @if($items->options->image != "")
                                                    <figure class="block-4-image">
                                                        <img src="{{ asset('uploads/products/'.$items->options->image)}}" alt="Image placeholder" class="img-fluid" width="70" height="70">
                                                    </figure>
                                                @else
                                                    <figure class="block-4-image">
                                                        <img src="{{ asset('front-assets/images/men.jpg') }}" alt="Image placeholder" class="img-fluid">
                                                    </figure>
                                                @endif
                                            </td>
                                            <td class="product-name">
                                                <h2 class="h5 text-black">{{ $items->name }}</h2>
                                            </td>
                                            <td>${{ $items->price }}</td>
                                            <td>
                                                <div class="input-group mb-3" style="max-width: 120px;">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-outline-primary sub" data-id="{{$items->rowId}}" type="button">&minus;</button>
                                                </div>
                                                <input type="text" class="form-control text-center" value="{{ $items->qty }}" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary add" data-id="{{$items->rowId}}" type="button">&plus;</button>
                                                </div>
                                                </div>
                                            </td>
                                            <td>${{ $items->price * $items->qty }}</td>
                                            <td><a href="#" class="btn btn-primary btn-sm" onclick="deleteItem('{{ $items->rowId }}');">X</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                        <div class="row mb-5">
                            {{-- <div class="col-md-6 mb-3 mb-md-0">
                            <button class="btn btn-primary btn-sm btn-block">Update Cart</button>
                            </div> --}}
                            <div class="col-md-6">
                            <a href="{{ route('front.shop') }}"><button class="btn btn-outline-primary btn-sm btn-block">Continue Shopping</button></a>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-12">
                            <label class="text-black h4" for="coupon">Coupon</label>
                            <p>Enter your coupon code if you have one.</p>
                            </div>
                            <div class="col-md-8 mb-3 mb-md-0">
                            <input type="text" class="form-control py-3" id="coupon" placeholder="Coupon Code">
                            </div>
                            <div class="col-md-4">
                            <button class="btn btn-primary btn-sm">Apply Coupon</button>
                            </div>
                        </div> --}}
                        </div>
                        <div class="col-md-6 pl-5">
                        <div class="row justify-content-end">
                            <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-12 text-right border-bottom mb-5">
                                <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                <span class="text-black">Subtotal</span>
                                </div>
                                <div class="col-md-6 text-right">
                                <strong class="text-black">${{ Cart::Subtotal() }}</strong>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-6">
                                <span class="text-black">Total</span>
                                </div>
                                <div class="col-md-6 text-right">
                                <strong class="text-black">${{ Cart::Subtotal() }}</strong>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <a href="{{ route('front.checkout') }}"><button class="btn btn-primary btn-lg py-3 btn-block" >Proceed To Checkout</button></a>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <h4>Why nothing is here!</h4>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('customJs')
    <script>
        // Cart + and - events
        $('.add').click(function(){
            var qtyElement = $(this).parent().prev(); // Qty Input
            var qtyValue = parseInt(qtyElement.val()); // parseInt converts string to integer
            if (qtyValue < 10) {
                qtyElement.val(qtyValue+1);

                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId,newQty)
            }            
        });

        $('.sub').click(function(){
            var qtyElement = $(this).parent().next(); 
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue > 1) {
                qtyElement.val(qtyValue-1);

                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId,newQty)
            }        
        });

        // Cart price update after + or -
        // Through ajax
        function updateCart(rowId, qty){
            $.ajax({
                url: '{{ route("front.updateCart") }}',
                type: 'post',
                data: {rowId:rowId, qty:qty},
                dataType: 'json',
                success: function(response){
                    window.location.href = '{{ route("front.cart") }}';
                }
            });
        }

        // Delete item
        function deleteItem(rowId){
            if (confirm("Are you sure you want to delete?")) {
                $.ajax({
                    url: '{{ route("front.deleteItem.cart") }}',
                    type: 'post',
                    data: {rowId:rowId},
                    dataType: 'json',
                    success: function(response){
                        window.location.href = '{{ route("front.cart") }}';
                    }
                });
            }
        }
    </script>
@endsection