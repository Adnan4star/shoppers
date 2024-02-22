@extends('admin.layouts.app') {{--This is child layout and i am calling parent layout(app.blade.php)--}}

@section('content') {{--calling dynamic content with same name provided in parent directory--}}
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Create Coupon Code
                    </h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('coupons.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container">
            <div class="row row-cards">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="form-responsive" style="margin-top: 20px; margin-left: 20px; margin-right: 20px;">
                            <form class="form-horizontal form-vcenter" action="" method="POST" id="discountForm" name="discountForm">
                                <div class="card-body">
                                    <div class="mb-3 row">
                                        <label for="code" class="col-3 col-form-label required">Code</label>
                                        <div class="col">
                                            <input type="text" name="code" id="code" class="form-control" aria-describedby="nameHelp" placeholder="Enter coupon code">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="name" class="col-3 col-form-label">Name</label>
                                        <div class="col">
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="description" class="col-3 col-form-label">Description</label>
                                        <div class="col">
                                            <textarea type="text" name="description" id="description" rows="5" cols="30" class="form-control" placeholder="Description"></textarea>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="max_uses" class="col-3 col-form-label">Max Uses</label>
                                        <div class="col">
                                            <input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="Max uses">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="max_uses_user" class="col-3 col-form-label">Max Uses User</label>
                                        <div class="col">
                                            <input type="text" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="Max uses allowed per user">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="type" class="col-3 col-form-label required">Type</label>
                                        <div class="col">
                                            <select name="type" id="type" class="form-control">
                                                <option value="percent">Percent</option>
                                                <option value="fixed">Fixed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="discount_amount" class="col-3 col-form-label required">Discount Amount</label>
                                        <div class="col">
                                            <input type="text" name="discount_amount" id="discount_amount" class="form-control" placeholder="Discount amount">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="min_amount" class="col-3 col-form-label">Minimum Amount</label>
                                        <div class="col">
                                            <input type="text" name="min_amount" id="min_amount" class="form-control" placeholder="Minimum amount">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="status" class="col-3 col-form-label required">Status</label>
                                        <div class="col">
                                            <select name="status" id="status" class="form-control">
                                                <option value="1">Active</option>
                                                <option value="0">Block</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="starts_at" class="col-3 col-form-label">Starts At</label>
                                        <div class="col">
                                            <input type="text" name="starts_at" id="starts_at" class="form-control" placeholder="Starts at">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="expires_at" class="col-3 col-form-label">Expires At</label>
                                        <div class="col">
                                            <input type="text" name="expires_at" id="expires_at" class="form-control" placeholder="Expires at">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button type="submit" class="btn btn-primary">Create</button>
                                        <a href="{{ route('coupons.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
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
    <script>
        // DateTime picker PHP tech life 32/59
        $(document).ready(function(){
            $('#starts_at').datetimepicker({
                format:'Y-m-d H:i:s',
            });
        });

        $(document).ready(function(){
            $('#expires_at').datetimepicker({
                format:'Y-m-d H:i:s',
            });
        });

        // Form submission
        $("#discountForm").submit(function(event){
            event.preventDefault();

            var element = $(this);
            $("button[type=submit]").prop('disabled',true);

            $.ajax({
                url: "{{ route('coupons.store') }}",
                type: 'post',
                data: element.serializeArray(), //serialize array will get the form enteries and pass on to ajax
                dataType: 'json',
                success: function(response){
                    $("button[type=submit]").prop('disabled',false);

                    if(response['status'] == true){
                        window.location.href = "{{ route('coupons.index') }}";
                        
                        $("#code").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                        
                        $("#discount_amount").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        $("#starts_at").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");

                        $("#expires_at").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback').html("");
                        
                    }else{
                        var errors = response['errors'];

                        if(errors['code']){
                            $("#code").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['code']);
                        }else{
                            $("#code").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }
                        
                        if(errors['discount_amount']){
                            $("#discount_amount").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['discount_amount']);
                        }else{
                            $("#discount_amount").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                        if(errors['starts_at']){
                            $("#starts_at").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['starts_at']);
                        }else{
                            $("#starts_at").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }

                        if(errors['expires_at']){
                            $("#expires_at").addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback').html(errors['expires_at']);
                        }else{
                            $("#expires_at").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        }
                        
                    }
                },  error: function(jqXHR, exception){
                        console.log("Something went wrong"); //jqXHR identifies the error
                    }
            })
        });
    </script>
@endsection
