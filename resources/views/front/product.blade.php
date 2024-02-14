@extends('front.layouts.app')

@section('content')

<div class="bg-light py-3">
    <div class="container">
      <div class="row">
        <div class="col-md-12 mb-0"><a href="{{route('front.home')}}">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Tank Top T-Shirt</strong></div>
      </div>
    </div>
  </div>  

  <div class="site-section">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
            @if($product->image != '')
                <img src="{{ asset('uploads/products/'.$product->image) }}" alt="Image" class="img-fluid">
            @else
                <img src="{{ asset('front-assets/images/cloth_1.jpg') }}" alt="Image" class="img-fluid">
            @endif
        </div>
        <div class="col-md-6">
          <h2 class="text-black">{{$product->title}}</h2>
          <p>{{$product->description}}</p>
          <p><strong class="text-primary h4">{{$product->price}}</strong></p>

          @if($product->compare_price > 0)
            <p  class="text-secondary h5"><del>{{$product->compare_price}}</del></p>
          @endif

          <div class="mb-5">
            <div class="input-group mb-3" style="max-width: 120px;">
            <div class="input-group-prepend">
              <button class="btn btn-outline-primary js-btn-minus" type="button">&minus;</button>
            </div>
            <input type="text" class="form-control text-center" value="1" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
            <div class="input-group-append">
              <button class="btn btn-outline-primary js-btn-plus" type="button">&plus;</button>
            </div>
            </div>
          </div>
          <p><a href="javascript:void(0);" onclick="addToCart({{ $product->id }})"  class="buy-now btn btn-sm btn-primary">Add To Cart</a></p>

        </div>
      </div>
    </div>
  </div>

  <div class="site-section block-3 site-blocks-2 bg-light">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-7 site-section-heading text-center pt-4">
          <h2>Featured Products</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="nonloop-block-3 owl-carousel">
            @if($featuredProducts != 'No')
              @foreach($featuredProducts as $featured)
                <div class="item">
                  <div class="block-4 text-center">
                    @if($featured->image != '')
                      <figure class="block-4-image">
                        <a href="{{ route("front.product",$featured->slug) }}"><img src="{{ asset('uploads/products/'.$featured->image) }}" alt="Image placeholder" class="img-fluid"></a>
                      </figure>
                      @else
                      <figure class="block-4-image">
                        <a href="{{ route("front.product",$featured->slug) }}"><img src="{{ asset('front-assets/images/cloth_1.jpg') }}" alt="Image placeholder" class="img-fluid"></a>
                      </figure>
                    @endif
                    <div class="block-4-text p-4">
                      <h3><a href="#">{{ $featured->title }}</a></h3>
                      <p class="mb-0">{{ $featured->description }}</p>
                      <p class="text-primary font-weight-bold">{{ $featured->price }}</p>
                    </div>
                  </div>
                </div>
              @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('customJs')
  <script>
    function addToCart(id){
      $.ajax({
        url: '{{ route("front.addToCart") }}',
        type: 'post',
        data: {id:id},
        dataType: 'json',
        success: function(response){
          if (response.status == true) {
            window.location.href = "{{ route('front.cart') }}";
          } else {
            alert(response.message);
          }
        }
      });
    }
  </script>
@endsection