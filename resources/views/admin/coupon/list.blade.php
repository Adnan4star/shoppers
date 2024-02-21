@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

@section('content') {{--calling dynamic content with same name provided in parent directory--}}
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Coupons
                </h2>
            </div>
            <div class="col-sm-5 text-right">
                <a href="{{ route('coupons.create') }}" class="btn btn-primary">New Coupon</a>
            </div>
            <div class="my-2 my-md-2 flex-grow-1 flex-md-grow-0 order-first order-md-last col-lg-10">
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
            <div class="col-lg-10">
                @include('admin.message')
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th width="10%">ID</th>
                                    <th width="10%">Code</th>
                                    <th width="10%">Name</th>
                                    <th width="10%">Discount</th>
                                    <th width="10%">Start Date</th>
                                    <th width="10%">End Date</th>
                                    <th width="10%">Status</th>
                                    <th width="30%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($discountCoupons->IsNotEmpty())
                                    @foreach ($discountCoupons as $discountCoupon)
                                    <tr>
                                        <td>{{ $discountCoupon->id }}</td>
                                        <td class="text-muted">
                                            {{ $discountCoupon->code }}
                                        </td>
                                        <td class="text-muted">
                                            {{ $discountCoupon->name }}
                                        </td>
                                        <td class="text-muted">
                                            @if ($discountCoupon->type == 'percent')
                                                {{ $discountCoupon->discount_amount }}%
                                            @else
                                                ${{ $discountCoupon->discount_amount }}
                                            @endif
                                        </td>
                                        <td class="text-muted">
                                            {{ (!empty($discountCoupon->starts_at)) ? \Carbon\Carbon::parse($discountCoupon->starts_at)->format('Y/m/d H:i:s') : '' }}
                                        </td>
                                        <td class="text-muted">
                                            {{ (!empty($discountCoupon->expires_at)) ? \Carbon\Carbon::parse($discountCoupon->expires_at)->format('Y/m/d H:i:s') : '' }}
                                        </td>
                                        <td class="text-muted">
                                            {{ $discountCoupon->status }}
                                        </td>
                                        <td>
                                            <a href="{{route('coupons.edit',$discountCoupon->id)}}" class="btn btn-primary">Edit</a>
                                            <a href="#" onclick="deleteCoupon({{ $discountCoupon->id }})" class="btn btn-danger">Delete</a>
                                        </td>
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
            </div>
        </div>
    </div>
</div>

@endsection

@section('customJs')
    <script>
        function deleteCoupon(id)
        {
            var url = '{{ route("coupons.delete","ID") }}';
            var newUrl = url.replace("ID",id);
            newUrl = url.replace("ID",id);
            
            if(confirm("Are you sure you want to delete?")){
                $.ajax({
                    url: newUrl,
                    type: 'delete',
                    data: {}, //serialize array will get the form enteries and pass on to ajax
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response){
                        $("button[type=submit]").prop('disabled',false);

                        if(response['status']){
                            window.location.href = "{{ route('coupons.index') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
