@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

    @section('content') {{--calling dynamic content with same name provided in parent directory--}}
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <h2 class="page-title">
                            Shipping Management
                        </h2>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('shipping.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container">
                @include('admin.message')
                <div class="row row-cards">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="form-responsive" style="margin-top: 20px; margin-left: 20px; margin-right: 20px;">
                                <form class="form-horizontal form-vcenter" action="" method="POST" id="shippingUpdate" name="shippingUpdate" enctype="multipart/form-data">
                                    
                                    <div class="mb-3 row">
                                        <label for="country" class="col-3 col-form-label">Country</label>
                                        <div class="col">
                                            <select name="country" id="country" class="form-control">
                                                <option value="">Select a Country</option>
                                                @if ($countries->isNotEmpty())
                                                    @foreach ($countries as $country)
                                                        <option {{ ($shipping->country_id == $country->id) ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                    <option {{ ($shipping->country_id == 'others') ? 'selected' : '' }} value="others">Others</option>
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <label for="amount" class="col-3 col-form-label">Amount</label>
                                        <div class="col">
                                            <input value="{{ $shipping->amount }}" type="text" name="amount" id="amount" class="form-control" placeholder="amount">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-3 col-form-label"></label>
                                        <div class="col">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>                                        
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    @endsection
    
@section('customJs')
    <script> //validating form through ajax
        $("#shippingUpdate").submit(function(event){
            event.preventDefault();

            var element = $(this);
            $("button[type=submit]").prop('disabled',true);

            $.ajax({
                url: "{{ route('shipping.update',$shipping->id) }}",
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled',false);

                    if(response['status'] == true){
                        window.location.href = "{{ route('shipping.index') }}";
                        
                    }else{
                        var errors = response['errors'];
                        if(errors['country']){
                            $("#country").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['country']);
                        }else{
                            $("#country").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }
                        
                        if(errors['amount']){
                            $("#amount").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['amount']);
                        }else{
                            $("#amount").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }
                    }
                },
                error: function(jqXHR, exception){
                    console.log("Something went wrong"); //jqXHR identifies the error
                }
            })
        });
    </script>
@endsection
