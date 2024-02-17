@extends('front.layouts.app')

@section('content')
    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="{{ route('front.home') }}">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Thankyou</strong></div>
            </div>
        </div>
    </div>  

    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    @if(Session::has('success'))
                        <div class="col-md-12">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ Session::get('success') }}
                                {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
                            </div>
                        </div>
                    @endif
                    <span class="icon-check_circle display-3 text-success"></span>
                    <h2 class="display-3 text-black">Thank you!</h2>
                    <p class="lead mb-5">You order id is {{ $id }} </p>
                    <p><a href="{{ route('front.shop') }}" class="btn btn-sm btn-primary">Back to shop</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection