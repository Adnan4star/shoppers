@extends('front.layouts.app')

@section('content')

<div class="bg-light py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0"><a href="{{ route('front.home') }}">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Shop</strong></div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        
        <div class="row mb-5">
            <div class="col-md-9 order-2">
                
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <div class="float-md-left mb-4"><h2 class="text-black h5">Shop All</h2></div>
                        <div class="d-flex">
                            <div class="dropdown mr-1 ml-md-auto">
                                
                            </div>
                            <div class="btn-group">
                                <select name="sort" id="sort" class="form-control">
                                    <option value="latest" {{ ($sort == 'latest') ? 'selected' : '' }}>Latest</option>
                                    <option value="price_desc" {{ ($sort == 'price_desc') ? 'selected' : '' }}>Price High</option>
                                    <option value="price_asc"  {{ ($sort == 'price_asc') ? 'selected' : '' }}>Price Low</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                @if($products->isNotEmpty())
                    <div class="row mb-5">
                        @foreach($products as $product)
                            <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                                <div class="block-4 text-center border">
                                    @if($product->image != "")
                                        <figure class="block-4-image">
                                            <a href="{{ route("front.product",$product->slug) }}"><img src="{{ asset('uploads/products/'.$product->image)}}" alt="Image placeholder" class="img-fluid"></a>
                                        </figure>
                                    @else
                                        <figure class="block-4-image">
                                            <a href="{{ route("front.product",$product->slug) }}"><img src="{{ asset('front-assets/images/men.jpg') }}" alt="Image placeholder" class="img-fluid"></a>
                                        </figure>
                                    @endif
                                        <div class="block-4-text p-4">
                                            <h3><a href="shop-single.html">{{ $product->title }}</a></h3>
                                            <p class="mb-0">{{ $product->description }}</p>
                                            <p class="text-primary font-weight-bold">{{ $product->price }}</p>
                                            @if($product->compare_price > 0)
                                                <p class="text-primary text-underline"><del>{{ $product->compare_price }}</del></p>
                                            @endif
                                        </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="row" data-aos="fade-up">
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
            
            
            
            <div class="col-md-3 order-1 mb-5 mb-md-0">
                <div class="border p-4 rounded mb-4">
                    <h3 class="mb-3 h6 text-uppercase text-black d-block">Categories</h3>
                    @if($categories->isNotEmpty())
                    <ul class="list-unstyled mb-0 ">
                        @foreach($categories as $category)
                            <li class="mb-4">
                                <a href="{{ route("front.shop",$category->slug) }}" class="d-flex category-toggle">
                                    <span>{{$category->name}}</span>
                                    <span class="text-black ml-auto">({{$category->sub_category->count()}})</span>
                                </a>
                                {{-- @if($category->sub_category->isNotEmpty())
                                    <ul class="list-unstyled subcategories" style="display: none;">
                                        @foreach($category->sub_category as $subcategory)
                                            <li class="mb-1">
                                                <a href="{{ route("front.shop",[$category->slug,$subcategory->slug]) }}" class="d-flex">
                                                    <span>{{$subcategory->name}}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif --}}
                            </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                {{-- <div class="border p-4 rounded mb-4">
                    <h3 class="mb-3 h6 text-uppercase text-black d-block">Brands</h3>
                    @if($brands->isNotEmpty())
                        <ul class="list-unstyled mb-0">
                            @foreach($brands as $brand)
                                <li class="mb-4">
                                    <a href="#" class="d-flex category-toggle">
                                        <span for="">{{$brand->name}}</span> --}}
                                        {{-- <span class="text-black ml-auto">()</span> --}}
                                    {{-- </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div> --}}
                
                
                <div class="border p-4 rounded mb-4">
                    <div class="mb-4">
                        <h3 class="mb-3 h6 text-uppercase text-black d-block">Filter by Price</h3>
                        <div id="slider-range" class="border-primary"></div>
                        <input type="text" name="text" id="amount" class="form-control border-0 pl-0 bg-white" disabled="" />
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="site-section site-blocks-2">
                    <div class="row justify-content-center text-center mb-5">
                        <div class="col-md-7 site-section-heading pt-4">
                            <h2>Categories</h2>
                        </div>
                    </div>
                    @if($categories->isNotEmpty())
                        <div class="row">
                            @foreach($categories as $category)
                                <div class="col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade" data-aos-delay="">
                                    <a class="block-2-item" href="#">
                                        <figure class="image">
                                            <img src="{{ asset('uploads/'.$category->image) }}" alt="" class="img-fluid">
                                        </figure>
                                        <div class="text">
                                            <span class="text-uppercase">Collections</span>
                                            <h3>{{ $category->name }}</h3>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')
<script>
    $("#sort").change(function(){
        apply_filters();
    });

    function apply_filters(){
        var url = '{{ url()->current() }}?';

        url += '&sort='+$("#sort").val()

        window.location.href = url;
    }

    

    
</script>
@endsection