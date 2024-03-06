@extends('front.layouts.app')

@section('content')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="{{ route('front.home') }}">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">{{$page->name}}</strong></div>
            </div>
        </div>
    </div>  

    <div class="site-section border-bottom" data-aos="fade">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="block-16">
                        <figure>
                            <img src="{{ asset('front-assets/images/young-1.jpg') }}" alt="Image placeholder" class="img-fluid rounded">
                            <a href="https://vimeo.com/channels/staffpicks/93951774" class="play-button popup-vimeo"><span class="ion-md-play"></span></a>
                        </figure>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="site-section-heading pt-3 mb-4">
                        <h2 class="text-black">{{ $page->name }}</h2>
                    </div>
                    <p>{!! $page->content !!}</p>
                    <p></p>
                </div>
            </div>
        </div>
    </div>



@endsection