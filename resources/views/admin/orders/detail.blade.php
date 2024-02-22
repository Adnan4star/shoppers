@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

@section('content') {{--calling dynamic content with same name provided in parent directory--}}
<div class="hold-transition">
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                                <h1>Order: #{{ $order->id }}</h1>
                            </h2>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('orders.index') }}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main content -->
            <section class="content">
                <!-- Default box -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-9">
                            @include('admin.message')
                            <div class="card">
                                <div class="card-header pt-3">
                                    <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col" style="width: auto">
                                            <h1 class="h5 mb-3">Shipping Address</h1>
                                            <address>
                                                <strong>{{$order->first_name.' '.$order->last_name}}</strong><br>
                                                {{$order->address}}, {{$order->apartment}}<br>
                                                {{$order->city}}, {{$order->zip}}, {{$order->countryName}}<br>
                                                Phone: {{$order->phone}}<br>
                                                Email: {{$order->email}}
                                            </address>
                                            <strong>Shipped Date:</strong><br>
                                            @if (!empty($order->shipped_date))
                                                {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }}
                                            @else
                                                n/a
                                            @endif
                                        </div>
                                        <div class="col-sm-4 invoice-col" style="padding-left: 80px; width: auto;">
                                            <b>Invoice #123</b><br>
                                            <br>
                                            <b>Order ID:</b> {{$order->id}}<br>
                                            <b>Total:</b> ${{ number_format($order->grand_total,2) }}<br>
                                            <b>Status:</b> 
                                            <span class="text-success">
                                                @if ($order->status == 'pending')
                                                    <span class="badge bg-danger text-white">Pending</span>
                                                @elseif ($order->status == 'shipped')
                                                    <span class="badge bg-info text-white">Shipped</span>
                                                @elseif ($order->status == 'delivered')
                                                    <span class="badge bg-success text-white">Delivered</span>
                                                @else
                                                    <span class="badge bg-danger text-white">Cancelled</span>
                                                @endif
                                            </span>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive p-3">								
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th width="100">Price</th>
                                                <th width="100">Qty</th>                                        
                                                <th width="100">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orderItem as $item)
                                                <tr>
                                                    <td>{{ $item->name }}</td>
                                                    <td>${{ number_format($item->price,2) }} </td>                                        
                                                    <td> {{ $item->qty }} </td>
                                                    <td>${{ number_format($item->total,2) }} </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th colspan="3" class="text-right">Subtotal:</th>
                                                <td>${{ number_format($order->subtotal,2) }}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Discount:{{ (!empty($order->coupon_code)) ? '('.  $order->coupon_code . ')' : ''  }}</th>
                                                <td>${{ number_format($order->discount,2) }}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Shipping:</th>
                                                <td>${{ number_format($order->shipping,2) }}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="3" class="text-right">Grand Total:</th>
                                                <td>${{ number_format($order->grand_total,2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>								
                                </div>                            
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <form action="" method="POST" name="changeOrderStatusForm" id="changeOrderStatusForm">
                                    
                                    <div class="card-body">
                                        <h2 class="h4 mb-3">Order Status</h2>
                                        <div class="mb-3">
                                            <select name="status" id="status" class="form-control">
                                                <option value="pending" {{ ($order->status == 'pending') ? 'selected' : '' }}>Pending</option>
                                                <option value="shipped" {{ ($order->status == 'shipped') ? 'selected' : '' }}>Shipped</option>
                                                <option value="delivered" {{ ($order->status == 'delivered') ? 'selected' : '' }}>Delivered</option>
                                                <option value="cancelled" {{ ($order->status == 'cancelled') ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="shipped_date">Shipped Date</label>
                                            <input value="{{$order->shipped_date}}" type="text" name="shipped_date" id="shipped_date" class="form-control" placeholder="Shipped date">
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <form action="" method="POST" id="sendInvoiceEmail" name="sendInvoiceEmail">
                                <div class="card mt-2">
                                    <div class="card-body">
                                        <h2 class="h4 mb-3">Send Inovice Email</h2>
                                        <div class="mb-3">
                                            <select name="userType" id="userType" class="form-control">
                                                <option value="customer">Customer</option>                                                
                                                <option value="admin">Admin</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary">Send</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </section>
        </div>
    </div> 
</div>

@endsection

@section('customJs')
    <script>
        // DateTime picker
        $(document).ready(function(){
            $('#shipped_date').datetimepicker({
                format:'Y-m-d H:i:s',
            });
        });

        // changeOrderStatusForm
        $("#changeOrderStatusForm").submit(function(event){
            event.preventDefault();
            if (confirm("Are you sure you want to change status?")){
                $.ajax({
                    url: '{{ route("orders.changeOrderStatus", $order->id) }}',
                    type: 'post',
                    data: $(this).serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        window.location.href = '{{ route("orders.detail",$order->id) }}';
                    }
                });
            }
        }); 

        // User send Invoice Email
        $("#sendInvoiceEmail").submit(function(event){
            event.preventDefault();

            if (confirm("Are you sure you want to send an email?")){
                $.ajax({
                    url: '{{ route("orders.sendInvoiceEmail", $order->id) }}',
                    type: 'post',
                    data: $(this).serializeArray(),
                    dataType: 'json',
                    success: function(response){
                        window.location.href = '{{ route("orders.detail",$order->id) }}';
                    }
                });
            }
        });
    </script>
@endsection
