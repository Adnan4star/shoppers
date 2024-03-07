<!DOCTYPE html>
<html lang="en">
<head>
  <title>Shoppers &mdash; Colorlib e-Commerce Template</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}"> {{--attaching csrf token in header for ajax--}}
  
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700"> 
  <link rel="stylesheet" href="{{ asset('front-assets/fonts/icomoon/style.css') }}">
  
  <link rel="stylesheet" href="{{ asset('front-assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('front-assets/css/magnific-popup.css') }}">
  <link rel="stylesheet" href="{{ asset('front-assets/css/jquery-ui.css') }}">
  <link rel="stylesheet" href="{{ asset('front-assets/css/owl.carousel.min.css') }}">
  <link rel="stylesheet" href="{{ asset('front-assets/css/owl.theme.default.min.css') }}">
  
  <link rel="stylesheet" href="{{ asset('front-assets/css/rangeslider.css') }}">

  <link rel="stylesheet" href="{{ asset('front-assets/css/aos.css') }}">
  
  <link rel="stylesheet" href="{{ asset('front-assets/css/style.css') }}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
  .checked {
    color: orange;
  }
  .rating {
    direction: rtl;
    unicode-bidi: bidi-override;
    color: #ddd; /* Personal choice */
      font-size: 8px;
      margin-left: -15px;
  }
  .rating input {
    display: none;
  }
  .rating label:hover,
  .rating label:hover ~ label,
  .rating input:checked + label,
  .rating input:checked + label ~ label {
    color: #ffc107; /* Personal color choice. Lifted from Bootstrap 4 */
      font-size: 8px;
  }
  
  .front-stars, .back-stars, .star-rating {
      display: flex;
    }
    
    .star-rating {
      align-items: left;
      font-size: 1.5em;
      justify-content: left;
      margin-left: -5px;
    }
    
    .back-stars {
      color: #CCC;
      position: relative;
    }
    
    .front-stars {
      color: #FFBC0B;
      overflow: hidden;
      position: absolute;
      top: 0;
      transition: all 0.5s;
    }

    
    .percent {
      color: #bb5252;
      font-size: 1.5em;
    }

    .star-rating.product{
      font-size: 1em;
    }
    
</style>
  {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
  
</head>
<body>
  
  <div class="site-wrap">
    <header class="site-navbar" role="banner">
      <div class="site-navbar-top">
        <div class="container">
          <div class="row align-items-center">
            {{--Home search filtering--}}
            <div class="col-6 col-md-4 order-2 order-md-1 site-search-icon text-left">
              <form action="{{ route('front.shop') }}" method="get" class="site-block-top-search">
                <span class="icon icon-search2"></span>
                <input name="search" id="search" value="{{ Request::get('search') }}" type="text" class="form-control border-0" autocomplete="off" placeholder="Search">
              </form>
            </div>
            
            <div class="col-12 mb-3 mb-md-0 col-md-4 order-1 order-md-2 text-center">
              <div class="site-logo">
                <a href="{{ route('front.home') }}" class="js-logo-clone">Shoppers</a>
              </div>
            </div>
            
            <div class="col-6 col-md-4 order-3 order-md-3 text-right">
              <div class="site-top-icons">
                <ul>
                  @if (\Auth::check())
                    <li><a href="{{ route('account.profile') }}"><span class="icon icon-person"></span></a></li>
                  @else
                    <li><a href="{{ route('account.login') }}"><span class="icon icon-person"></span></a></li>
                  @endif
                    <li><a href="{{ route('account.wishlist') }}"><span class="icon icon-heart-o"></span></a></li>
                  <li>
                    <a href="{{ route('front.cart') }}" class="site-cart">
                      <span class="icon icon-shopping_cart"></span>
                      <span class="count">0</span>
                    </a>
                  </li> 
                  <li class="d-inline-block d-md-none ml-md-0"><a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a></li>
                </ul>
              </div> 
            </div>
            
          </div>
        </div>
      </div> 
      <nav class="site-navigation text-right text-md-center" role="navigation">
        <div class="container">
          <ul class="site-menu js-clone-nav d-none d-md-block">
            
            @if(getCategories()->isNotEmpty())
            @foreach(getCategories() as $category)
            <li class="has-children active">
              <a href="{{ route("front.shop",$category->slug) }}">{{$category->name}}</a>
              @if($category->sub_category->isNotEmpty()) {{--defined relation of sub_category in category model--}}
              <ul class="dropdown">
                @foreach($category->sub_category as $subcategory)
                <li><a href="{{ route('front.shop',[$category->slug,$subcategory->slug]) }}">{{$subcategory->name}}</a></li>
                @endforeach
              </ul>
              @endif
            </li>
            @endforeach
            @endif
          </ul>
        </div>
      </nav>
    </header>
    
    <main>
      @yield('content')
    </main>
    
    <footer class="site-footer border-top">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 mb-5 mb-lg-0">
            <div class="row">
              <div class="col-md-12">
                <h3 class="footer-heading mb-4">Navigations</h3>
              </div>
              <div class="col-md-6 col-lg-4">
                <ul class="list-unstyled">
                  @if (staticPages()->isNotEmpty())
                    @foreach(staticPages() as $page)
                      <li><a href="{{ route('front.page',$page->slug) }}">{{$page->name}}</a></li>
                    @endforeach
                  @endif
                </ul>
              </div>
              <div class="col-md-6 col-lg-4">
                <ul class="list-unstyled">
                  <li>Mobile commerce</li>
                  <li>Dropshipping</li>
                  <li>Website development</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
            <h3 class="footer-heading mb-4">Promo</h3>
            <a href="{{ route('front.shop') }}" class="block-6">
              <img src="{{ asset('front-assets/images/hero_1.jpg') }}" alt="Image placeholder" class="img-fluid rounded mb-4">
              <h3 class="font-weight-light  mb-0">Finding Your Perfect Shoes</h3>
              <p>Promo from March 1 &mdash; 17, 2024</p>
            </a>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="block-5 mb-5">
              <h3 class="footer-heading mb-4">Contact Info</h3>
              <ul class="list-unstyled">
                <li class="address">1,B MAIN BULEVARD, GILBERG LAHORE</li>
                <li class="phone"><a href="tel://23923929210">+(042) 35774046</a></li>
                <li class="email">info@tetralogicx.com</li>
              </ul>
            </div>
            
            <div class="block-7">
              <form action="#" method="post">
                <label for="email_subscribe" class="footer-heading">Subscribe</label>
                <div class="form-group">
                  <input type="text" class="form-control py-4" id="email_subscribe" placeholder="Email">
                  <input type="submit" class="btn btn-sm btn-primary" value="Send">
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <p>
              <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
              Copyright &copy;<script data-cfasync="false" src=""></script><script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank" class="text-primary">Colorlib</a>
              <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </p>
          </div>
          
        </div>
      </div>
    </footer>
    
    <!--Wishlist Modal -->
    <div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Success</h5>
          </div>
          <div class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="{{ asset('front-assets/js/jquery-3.3.1.min.js') }}"></script>
  <script src="{{ asset('front-assets/js/jquery-ui.js') }}"></script>
  <script src="{{ asset('front-assets/js/popper.min.js') }}"></script>
  <script src="{{ asset('front-assets/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('front-assets/js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('front-assets/js/jquery.magnific-popup.min.js') }}"></script>
  <script src="{{ asset('front-assets/js/aos.js') }}"></script>
  
  <script src="{{ asset('front-assets/js/main.js') }}"></script>
  <script src="{{ asset('front-assets/js/rangeslider.min.js') }}"></script>

  <script src="{{ asset('front-assets/js/slick.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  
  <script>
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
          }
      });

         // Add to wishlist method
      function addToWishlist(id){
        $.ajax({
          url: '{{ route("front.addToWishlist") }}',
          type: 'post',
          data: {id:id},
          dataType: 'json',
          success: function(response) {
            if (response.status == true) {
              $("#wishlistModal .modal-body").html(response.message);

              $("#wishlistModal").modal('show');
            }else {
              window.location.href = "{{ route('account.login') }}";
            }
          }
        });
      }
  </script>

  @yield('customJs');

  
</body>
</html>