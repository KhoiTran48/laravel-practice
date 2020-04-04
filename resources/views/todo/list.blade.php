@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">List Task</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @foreach ($todos as $todo)
                        <div class="d-flex">
                            <p>{{$todo->name}}</p>
                            <a href={{route("todo.edit", ["id"=>$todo->id])}} >
                                <button class="btn btn-primary">Edit</button>
                            </a>
                            <a href={{route("todo.delete", ["id"=>$todo->id])}} >
                                <button class="btn btn-primary">Delete</button>
                            </a>
                        </div>
                    @endforeach
                    <a href={{route("todo.create")}} >
                        <button class="btn btn-primary">Create</button>
                    </a>
                    @if (session('error_permission'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error_permission') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
