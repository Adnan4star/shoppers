@extends('front.layouts.app')

@section('content')
<div class="site-section">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-12">
                <h2 class="h3 mb-3 text-black d-flex justify-content-center align-items-center" style="color: #7971ea;">Forgot Password</h2>
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
                <form action="{{ route('front.processForgotPassword') }}" method="post">
                    @csrf
                    <div class="p-3 p-lg-5 border">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="email" class="text-black">Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                                @error('email')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" value="login">Submit</button>
                        </div>
                    </div>
                </form>
                <div class="text-center">Already registered? <a href="{{ route('account.login') }}">Login</a></a></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customJs')

@endsection