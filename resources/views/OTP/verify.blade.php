@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Enter OTP</div>

                <div class="card-body">
                    @if($errors->count() > 0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                    <form method="post" action={{ route("verifyOTP") }} >
                        @csrf
                        <div class="form-group">
                            <label for="otp">Your OTP</label>
                            <input type="text" name="otp" id="otp" class="form-control" require>
                        </div>
                        <input type="submit" value="Verify" class="btn btn-info">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
