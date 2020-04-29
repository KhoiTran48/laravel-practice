@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form method="post" action={{ route("upload") }} enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="avatar"/>
                        <input type="submit" value="Upload">
                    </form>
                    <upload-form :user="{{ auth()->user() }}"></upload-form>
                </div>
            </div>
        </div>
    </div>
    @foreach($avatars as $avatar)
    <div class="card" style="width: 18rem;">
        {{-- <img class="card-img-top" src="..." alt="Card image cap"> --}}
        {{ $avatar }}
        <div class="card-body">
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
        </div>
    </div>
    @endforeach
</div>
@endsection
