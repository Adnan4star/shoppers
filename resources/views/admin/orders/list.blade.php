@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

@section('content') {{--calling dynamic content with same name provided in parent directory--}}
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Orders
                </h2>
            </div>
            <div class="col-auto">
            </div>
            <div class="my-2 my-md-2 flex-grow-1 flex-md-grow-0 order-first order-md-last col-lg-12">
                <form action="" method="get" autocomplete="off" novalidate>
                    {{-- <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route('categories.index') }}'" class="btn btn-success btn-sm form-control">Reset</button>
                    </div> --}}
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                            <svg xmlns="{{ asset('admin-assets/http://www.w3.org/2000/svg') }}" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                        </span>
                        <input type="text" name="keyword" value="{{ Request::get('keyword') }}" class="form-control" placeholder="Search…" aria-label="Search in website">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-lg-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th width="10%">Orders #</th>
                                    <th width="20%">Customer</th>
                                    <th width="20%">Email</th>
                                    <th width="10%">Phone</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Total</th>
                                    <th width="20%">Date Purchased</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($orders->isNotEmpty())
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td><a href="{{ route('orders.detail',$order->id) }}">{{ $order->id }}</a></td>
                                            <td>{{ $order->name }}</td>
                                            <td>{{ $order->email }}</td>
                                            <td>{{ $order->phone }}</td>
                                            <td>
                                                @if ($order->status == 'pending')
                                                    <span class="badge bg-danger text-white">Pending</span>
                                                @elseif ($order->status == 'shipped')
                                                    <span class="badge bg-info text-white">Shipped</span>
                                                @elseif ($order->status == 'delivered')
                                                    <span class="badge bg-success text-white">Delivered</span>
                                                @else
                                                    <span class="badge bg-danger text-white">Cancelled</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($order->grand_total,2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">Records Not Found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                {{--pagination--}}
                <div class="row mt-2" data-aos="fade-up">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('customJs')
    <script>

    </script>
@endsection
