@extends('front.layouts.app')

@section('content')
    
<div class="site-blocks-cover" style="background-image: url({{ asset('front-assets/images/hero_1.jpg') }});" data-aos="fade">
  <div class="container">
    <div class="row align-items-start align-items-md-center justify-content-end">
      <div class="col-md-5 text-center text-md-left pt-5 pt-md-0">
        <h1 class="mb-2">Finding Your Perfect Shoes</h1>
        <div class="intro-text text-center text-md-left">
          <p class="mb-4">"Wearing the right shoes isn't just about style; it's about respecting the journey your feet take you on." </p>
          <p>
            <a href="{{ route('front.shop') }}" class="btn btn-sm btn-primary">Shop Now</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="site-section site-section-sm site-blocks-1">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="">
        <div class="icon mr-4 align-self-start">
          <span class="icon-truck"></span>
        </div>
        <div class="text">
          <h2 class="text-uppercase">Free Shipping</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus at iaculis quam. Integer accumsan tincidunt fringilla.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="100">
        <div class="icon mr-4 align-self-start">
          <span class="icon-refresh2"></span>
        </div>
        <div class="text">
          <h2 class="text-uppercase">Free Returns</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus at iaculis quam. Integer accumsan tincidunt fringilla.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="200">
        <div class="icon mr-4 align-self-start">
          <span class="icon-help"></span>
        </div>
        <div class="text">
          <h2 class="text-uppercase">Customer Support</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus at iaculis quam. Integer accumsan tincidunt fringilla.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="site-section site-blocks-2">
  <div class="container">
    <div class="row">
      @if(getCategories()->isNotEmpty())
        @foreach(getCategories() as $category)
          <div class="col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0 mt-2" data-aos="fade" data-aos-delay="">
            <a class="block-2-item" href="{{ route('front.shop',$category->slug) }}">

              @if($category->image != "")
                <figure class="image">
                  <img src="{{ asset('uploads/'.$category->image) }}" alt="" class="img-fluid">
                </figure>
                @else
                <figure class="image">
                  <img src="{{ asset('front-assets/images/men.jpg') }}" alt="" class="img-fluid">
                </figure>
              @endif

              <div class="text">
                <span class="text-uppercase">Collections</span>
                <h3>{{$category->name}}</h3>
              </div>
            </a>
          </div>
        @endforeach
      @endif
    
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
          
          @if($featuredProducts->isNotEmpty()) {{--fetching featured products in front controller--}}
            @foreach($featuredProducts as $product)

              <div class="item">
                <div class="block-4 text-center">
                  <a href="{{ route("front.product",$product->slug) }}" class="product-img">
                    @if($product->image != "")
                      <figure class="block-4-image">
                        <img src="{{ asset('uploads/products/'.$product->image) }}" alt="Image placeholder" class="img-fluid">
                      </figure>
                    @else
                      <figure class="block-4-image">
                        <img src="{{ asset('front-assets/images/cloth_1.jpg') }}" alt="Image placeholder" class="img-fluid">
                      </figure>
                    @endif
                  </a>
                  
                  {{--addToWishlist() function method defined in app.blade--}}
                  <div class="overlay">
                    <a onclick="addToWishlist({{ $product->id }})" href="javascript:void(0)"><span class="icon icon-heart-o"></span></a>
                  </div>  

                  <div class="block-4-text p-4">
                    <h3><a href="{{ route("front.product",$product->slug) }}">{{$product->title}}</a></h3>
                    <p class="mb-0">{{$product->description}}</p>
                    <p class="text-primary font-weight-bold">{{$product->price}}</p>
                    @if($product->compare_price > 0)
                      <p class="text-primary text-underline"><del>{{$product->compare_price}}</del></p>
                    @endif
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

<div class="site-section block-8">
  <div class="container">
    <div class="row justify-content-center  mb-5">
      <div class="col-md-7 site-section-heading text-center pt-4">
        <h2>Big Sale!</h2>
      </div>
    </div>
    <div class="row align-items-center">
      <div class="col-md-12 col-lg-7 mb-5">
        <a href="{{ route('front.shop') }}"><img src="{{ asset('front-assets/images/blog_1.jpg') }}" alt="Image placeholder" class="img-fluid rounded"></a>
      </div>
      <div class="col-md-12 col-lg-5 text-center pl-md-5">
        <h2><a href="#">50% less in all items</a></h2>
        <p class="post-meta mb-4">By <a href="#">Carl Smith</a> <span class="block-8-sep">&bullet;</span> September 3, 2018</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam iste dolor accusantium facere corporis ipsum animi deleniti fugiat. Ex, veniam?</p>
        <p><a href="{{ route('front.shop') }}" class="btn btn-primary btn-sm">Shop Now</a></p>
      </div>
    </div>
  </div>
</div>
@endsection