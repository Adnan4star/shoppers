@extends('front.layouts.app')

@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">My Account</a></li>
                        <li class="breadcrumb-item"><strong>My Order Details</strong></li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-11 ">
            <div class="container  mt-4">
                <div class="row">
                    <div class="col-md-3">
                        @include('front.account.common.sidebar')
                    </div>
                    <div class="col-md-9">
                        <div class="card" style="margin-top: 20px">
                            <div class="card-header">
                                <h2 class="h5 mb-0 pt-2 pb-2"><strong>Order ID: {{ $order->id }}</strong></h2>
                            </div>
                            <div class="card-body pb-3">
                                <!-- Info -->
                                <div class="card card-sm">
                                    <div class="card-body bg-light mb-3">
                                        <div class="row">
                                            <div class="col-6 col-lg-3">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Order No:</h6>
                                                <!-- Text -->
                                                <p class="mb-lg-0 fs-sm fw-bold">
                                                    {{ $order->id }}
                                                </p>
                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Shipped date:</h6>
                                                <!-- Text -->
                                                <p class="mb-lg-0 fs-sm fw-bold">
                                                    <time datetime="2019-10-01">
                                                        @if (!empty($order->shipped_date))
                                                            {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }}
                                                        @else
                                                            n/a
                                                        @endif
                                                    </time>
                                                </p>
                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Status:</h6>
                                                <!-- Text -->
                                                <p class="mb-0 fs-sm fw-bold">
                                                    @if ($order->status == 'pending')
                                                        <span class="badge bg-danger text-white">Pending</span>
                                                    @elseif ($order->status == 'shipped')
                                                        <span class="badge bg-info text-white">Shipped</span>
                                                    @elseif ($order->status == 'delivered')
                                                        <span class="badge bg-success text-white">Delivered</span>
                                                    @else
                                                        <span class="badge bg-danger text-white">Cancelled</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-6 col-lg-3">
                                                <!-- Heading -->
                                                <h6 class="heading-xxxs text-muted">Order Amount:</h6>
                                                <!-- Text -->
                                                <p class="mb-0 fs-sm fw-bold">
                                                ${{ number_format($order->grand_total,2)}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="card-footer p-3">
    
                                <!-- Heading -->
                                <h6 class="mb-7 h5 mt-4"><strong>Order Items ({{ $orderItemsCount }})</strong></h6>
    
                                <!-- Divider -->
                                <hr class="my-3">
    
                                <!-- List group -->
                                <ul>
                                    @foreach ($orderItems as $item)
                                        <li class="list-group-item">
                                            <div class="row align-items-center">
                                                <div class="col-4 col-md-3 col-xl-2">
                                                    <!-- Image is fetched from helper function check getProductImage() in helper.php-->
                                                    @php $productImage = getProductImage($item->product_id); @endphp

                                                    @if($productImage->image != "")
                                                        <figure class="block-4-image">
                                                        <img src="{{ asset('uploads/products/'.$productImage->image) }}" alt="Image placeholder" class="img-fluid">
                                                        </figure>
                                                    @else
                                                        <figure class="block-4-image">
                                                        <img src="{{ asset('front-assets/images/cloth_1.jpg') }}" alt="Image placeholder" class="img-fluid">
                                                        </figure>
                                                    @endif
                                                </div>
                                                <div class="col">
                                                    <!-- Title -->
                                                    <p class="mb-4 fs-sm fw-bold">
                                                        <a class="text-body" href="product.html">{{ $item->name }} x {{ $item->qty }}</a> <br>
                                                        <span class="text-muted">${{ number_format($item->total,2) }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div> 
                        </div>
                            <div class="card card-lg mb-5 mt-3">
                                <div class="card-body">
                                    <!-- Heading -->
                                    <h6 class="mt-0 mb-3 h5"><strong>Order Total</strong></h6>
    
                                    <!-- List group -->
                                    <ul>
                                        <li class="list-group-item d-flex">
                                            <span><strong>Subtotal:&nbsp;</strong></span>
                                            <span class="ms-auto">${{ number_format($order->subtotal,2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <span><strong>Discount:&nbsp;{{ (!empty($order->coupon_code)) ? '('. $order->coupon_code .')' : '' }}</strong></span>
                                            <span class="ms-auto">&nbsp; ${{ number_format($order->discount,2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex">
                                            <span><strong>Shipping:&nbsp;</strong></span>
                                            <span class="ms-auto">${{ number_format($order->shipping,2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex fs-lg fw-bold">
                                            <span><strong>Grand Total:&nbsp;</strong></span>
                                            <span class="ms-auto">${{ number_format($order->grand_total,2) }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection