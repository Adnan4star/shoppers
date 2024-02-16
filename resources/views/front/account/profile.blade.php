@extends('front.layouts.app')

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h4 class="page-title">
                        <div class="col-md-8 mb-0"><a href="#">My Account</a><span class="mx-1 mb-2">/</span> <strong class="text-black">Settings</strong></div>
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="card">
        <div class="row g-0">
            
            @include('front.account.common.sidebar')

            <form action="#" method="POST">
                <div class="card-body">
                    <h2 class="mb-4">Personal Information</h2>
                    <h3 class="card-title mt-4">Name</h3>
                    <div>
                        <div class="row g-2">
                            <div class="col-auto">
                                <input type="text" class="form-control w-auto" name="name" id="name" placeholder="Enter your name">
                            </div>
                        </div>
                    </div>
                    <h3 class="card-title mt-4">Email</h3>
                    <div>
                        <div class="row g-2">
                            <div class="col-auto">
                                <input type="text" class="form-control w-auto" name="email" id="email" placeholder="Enter your email">
                            </div>
                        </div>
                    </div>
                    <h3 class="card-title mt-4">Phone</h3>
                    <div>
                        <div class="row g-2">
                            <div class="col-auto">
                                <input type="text" class="form-control w-auto" name="phone" id="phone" placeholder="Enter your phone">
                            </div>
                        </div>
                    </div>
                    <h3 class="card-title mt-4">Address</h3>
                    <div>
                        <div class="row g-2">
                            <div class="col-auto">
                                <input type="text" class="form-control w-auto" name="address" id="address" placeholder="Enter your address">
                            </div>
                        </div>
                    </div>
                    <div class="bg-transparent mt-4">
                        <div class="btn-list justify-content-end"><a href="#" class="btn">Cancel</a>
                            <a href="#" class="btn btn-primary">Submit</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection