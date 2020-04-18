@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Admin Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="post" action={{ route("upload") }} enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="avatar"/>
                        <input type="submit" value="Upload">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
