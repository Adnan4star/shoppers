@extends('front.layouts.app')

@section('content')

<div class="bg-light py-3">
    <div class="container">
      <div class="row">
        <div class="col-md-12 mb-0"><a href="{{route('front.home')}}">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">{{$product->title}}</strong></div>
      </div>
    </div>
  </div>  

  <div class="site-section">
    <div class="container">
      @include('front.account.common.message')
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
          <div class="star-rating product mt-2" title="">
            <div class="back-stars">
              <i class="fa fa-star" aria-hidden="true"></i>
              <i class="fa fa-star" aria-hidden="true"></i>
              <i class="fa fa-star" aria-hidden="true"></i>
              <i class="fa fa-star" aria-hidden="true"></i>
              <i class="fa fa-star" aria-hidden="true"></i>
              
              <div class="front-stars" style="width: {{ $avgRatingPercent }}%">
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
              </div>
            </div>
            <small class="pt-0 pl-1">({{ ($product->product_ratings_count > 1) ? $product->product_ratings_count. ' Reviews' : $product->product_ratings_count. ' Review'}})</small>
          </div>
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
          @if ($product->track_qty == 'Yes')
            @if ($product->qty > 0)
              <a onclick="addToWishlist({{ $product->id }})" href="javascript:void(0)"><span class="buy-now btn btn-sm btn-primary icon icon-heart-o"></span></a>
              <p><a href="javascript:void(0);" onclick="addToCart({{ $product->id }})"  class="buy-now btn btn-sm btn-primary mt-2">Add To Cart</a></p>
            @else 
              <p><a href="javascript:void(0);" class="buy-now btn btn-sm btn-primary">Out of Stock</a></p>
            @endif
          @else
            <p><a onclick="addToWishlist({{ $product->id }})" href="javascript:void(0)"><span class="icon icon-heart-o"></span></a></p>
            <p><a href="javascript:void(0);" onclick="addToCart({{ $product->id }})"  class="buy-now btn btn-sm btn-primary">Add To Cart</a></p>
          @endif
        </div>
      </div>
    </div>
  </div>
  <section class="section-7 pt-3 mb-3">
    <div class="container">
      <div class="row ">
        <div class="col-md-12 mt-5">
          <div class="bg-light">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                  <div class="col-md-8">
                    <div class="col">

                      <form action="" name="productRatingForm" id="productRatingForm" method="POST">
                        <h3 class="h4 pb-3 mt-2">Write a Review</h3>
                        <div class="form-group col-md-6 mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name">
                            <p></p>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                            <p></p>
                        </div>
                        <div class="form-group mb-3 col-md-6">
                          <label for="rating">Rating</label>
                          <br>
                          <div class="rating" style="width: 10rem ">
                              <input id="rating-5" type="radio" name="rating" value="5"/><label for="rating-5"><i class="fas fa-3x fa-star"></i></label>
                              <input id="rating-4" type="radio" name="rating" value="4"  /><label for="rating-4"><i class="fas fa-3x fa-star"></i></label>
                              <input id="rating-3" type="radio" name="rating" value="3"/><label for="rating-3"><i class="fas fa-3x fa-star"></i></label>
                              <input id="rating-2" type="radio" name="rating" value="2"/><label for="rating-2"><i class="fas fa-3x fa-star"></i></label>
                              <input id="rating-1" type="radio" name="rating" value="1"/><label for="rating-1"><i class="fas fa-3x fa-star"></i></label>
                          </div>
                          <p class="product-rating-error text-danger"></p>
                        </div>
                          <div class="form-group mb-3">
                              <label for="experience">How was your overall experience?</label>
                              <textarea name="comment"  id="comment" class="form-control" cols="30" rows="10" placeholder="How was your overall experience?"></textarea>
                              <p></p>
                          </div>
                          <div>
                              <button type="submit" class="btn btn-dark">Submit</button>
                          </div>
                      </form>
                    </div>
                  </div>
                  <div class="col-md-12 mt-5">
                    <div class="overall-rating mb-3">
                      <div class="d-flex">
                        <h1 class="h3 pe-3">{{ $avgRating }}</h1>
                          <div class="star-rating pt-0 pl-2" title="">
                            <div class="back-stars">
                              <i class="fa fa-star" aria-hidden="true"></i>
                              <i class="fa fa-star" aria-hidden="true"></i>
                              <i class="fa fa-star" aria-hidden="true"></i>
                              <i class="fa fa-star" aria-hidden="true"></i>
                              <i class="fa fa-star" aria-hidden="true"></i>
                              
                              <div class="front-stars" style="width: {{ $avgRatingPercent }}%">
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                              </div>
                            </div>
                          </div>  
                        <div class="pt-1 pl-1">({{ ($product->product_ratings_count > 1) ? $product->product_ratings_count. ' Reviews' : $product->product_ratings_count. ' Review'}})</div>
                      </div>
                        
                    </div>
                    @if ($product->product_ratings->isNotEmpty())
                      @foreach ($product->product_ratings as $rating)

                      @php 
                        $ratingPercentage = ($rating->rating*100)/5; // Finding percentage
                      @endphp
                        <div class="rating-group mb-4">
                          <span> <strong>{{ $rating->username }} </strong></span>
                          <div class="star-rating mt-2" title="">
                              <div class="back-stars">
                                  <i class="fa fa-star" aria-hidden="true"></i>
                                  <i class="fa fa-star" aria-hidden="true"></i>
                                  <i class="fa fa-star" aria-hidden="true"></i>
                                  <i class="fa fa-star" aria-hidden="true"></i>
                                  <i class="fa fa-star" aria-hidden="true"></i>
                                  
                                  <div class="front-stars" style="width: {{ $ratingPercentage }}%">
                                      <i class="fa fa-star" aria-hidden="true"></i>
                                      <i class="fa fa-star" aria-hidden="true"></i>
                                      <i class="fa fa-star" aria-hidden="true"></i>
                                      <i class="fa fa-star" aria-hidden="true"></i>
                                      <i class="fa fa-star" aria-hidden="true"></i>
                                  </div>
                              </div>
                          </div>   
                          <div class="my-3">
                            <p>
                              {{ $rating->comment }}
                            </p>
                          </div>
                        </div>
                      @endforeach
                    @endif
                </div>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>
  </section>  

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

    // Product rating form submit in ShopController
    $("#productRatingForm").submit(function(event){
      event.preventDefault();

      $.ajax({
        url: '{{ route("front.saveRating",$product->id) }}',
        type: 'post',
        data: $(this).serializeArray(),
        dataType: 'json',
        success: function(response){
          var errors = response.errors;
          
          if (response.status == true) {

            window.location.href = "{{ route('front.product', $product->slug) }}";
          
          } else {
            if (errors.name) {
            $("#name").addClass('is-invalid')
              .siblings("p")
              .addClass('invalid-feedback')
              .html(errors.name);
            } else {
              $("#name").removeClass('is-invalid')
                .siblings("p")
                .removeClass('invalid-feedback')
                .html('');
            }

            if (errors.email) {
              $("#email").addClass('is-invalid')
                .siblings("p")
                .addClass('invalid-feedback')
                .html(errors.email);
            } else {
              $("#email").removeClass('is-invalid')
                .siblings("p")
                .removeClass('invalid-feedback')
                .html('');
            }

            if (errors.comment) {
              $("#comment").addClass('is-invalid')
                .siblings("p")
                .addClass('invalid-feedback')
                .html(errors.comment);
            } else {
              $("#comment").removeClass('is-invalid')
                .siblings("p")
                .removeClass('invalid-feedback')
                .html('');
            }

            if (errors.rating) {
              $(".product-rating-error")
                .html(errors.rating);
            } else {
              $(".product-rating-error")
                .html('');
            }
          }

        }
      });

    });
  </script>
@endsection