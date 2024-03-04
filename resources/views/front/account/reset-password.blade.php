@extends('front.layouts.app')

@section('content')
<div class="site-section">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-12">
                <h2 class="h3 mb-3 text-black d-flex justify-content-center align-items-center" style="color: #7971ea;">Reset Password</h2>
                @if (Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                    </div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger">
                        {{ Session::get('error') }}
                    </div>
                @endif
            </div>
            
            <div class="col-md-7">
                <form action="{{ route('front.processResetPassword') }}" method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="p-3 p-lg-5 border">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="password" class="text-black">New Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" placeholder="New Password">
                                @error('new_password')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="password" class="text-black">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('confirm_password') is-invalid @enderror" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                                @error('confirm_password')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" value="reset_password">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')

@endsection