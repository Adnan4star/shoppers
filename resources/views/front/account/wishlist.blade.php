@extends('front.layouts.app')

@section('content')
<main>
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">My Account</a></li>
                    <li class="breadcrumb-item"><strong>My Wishlist</strong></li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            @include('admin.message')
            <div class="row">
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9 mb-2">
                    <div class="card-body">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2"><strong>Wishlist</strong></h2>
                        </div>
                        @if ($wishlists->isNotEmpty())
                            @foreach ($wishlists as $wishlist)
                                <div class="d-sm-flex justify-content-between mt-lg-4 mb-4 pb-3 pb-sm-2 border-bottom">
                                    <div class="d-block d-sm-flex align-items-start text-center text-sm-start">
                                        <a class="d-block flex-shrink-0 mx-auto me-sm-4" href="{{ route("front.product",$wishlist->product->slug) }}" style="width: 10rem;">
                                            @if($wishlist->product->image != "")
                                                <figure class="block-4-image">
                                                    <img src="{{ asset('uploads/products/'.$wishlist->product->image) }}" alt="Image placeholder" class="img-fluid">
                                                </figure>
                                            @else
                                                <figure class="block-4-image">
                                                    <img src="{{ asset('front-assets/images/cloth_1.jpg') }}" alt="Image placeholder" class="img-fluid">
                                                </figure>
                                            @endif
                                        </a>
                                        <div class="pt-2">
                                            <h3 class="product-title fs-base mb-2"><a href="{{ route("front.product",$wishlist->product->slug) }}">{{ $wishlist->product->title }}</a></h3>                                        
                                            <div class="fs-lg text-accent pt-2"><strong>${{ $wishlist->product->price }}</strong></div>
                                            @if($wishlist->product->compare_price > 0)
                                            <p class="text-primary text-underline"><del>{{$wishlist->product->compare_price}}</del></p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="pt-2 ps-sm-3 mx-auto mx-sm-0 text-center">
                                        <button onclick="removeProduct({{ $wishlist->product->id }})" class="btn btn-outline-danger btn-sm" type="button"><i class="fas fa-trash-alt me-2"></i>Remove</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="mt-3">
                                <h3 class="h5">Your Wishlist is empty!!</h3>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('customJs')
    <script>
        // removeProduct
        function removeProduct(id) {
            $.ajax({
                url: '{{ route("account.removeProductFromWishlist") }}',
                type: 'post',
                data: {id:id},
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = "{{ route('account.wishlist') }}";
                    }
                }
            });
        }
    </script>
@endsection