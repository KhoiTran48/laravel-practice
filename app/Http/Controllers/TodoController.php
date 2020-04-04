<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;
use App\Http\Requests\TodoRequest;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = Todo::where("user_id", Auth::user()->id)->get();
        return view("todo.list", ["todos" => $todos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("todo.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TodoRequest $request, Todo $todo)
    {
        
        $request["user_id"] = Auth::user()->id;
        Todo::create($request->all());
        return redirect()->route("todo.list")->with("status", "Add task successfully!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $todo = Todo::find($id);
        if(empty($todo) || $todo->user_id != Auth::user()->id){
            return redirect()->route("todo.list")->with(["error_permission" => "Permission denied!"]);
        }
        return view("todo.edit", ["todo" => $todo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TodoRequest $request, $id)
    {
        $todo = Todo::find($id);
        if(empty($todo) || $todo->user_id != Auth::user()->id){
            return redirect()->route("todo.list")->with(["error_permission" => "Permission denied!"]);
        }
        $todo->update($request->all());
        return redirect()->route("todo.list")->with("status", "Edit task successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = Todo::find($id);
        if(empty($todo) || $todo->user_id != Auth::user()->id){
            return redirect()->route("todo.list")->with(["error_permission" => "Permission denied!"]);
        }
        $todo->delete();
        return redirect()->route("todo.list")->with("status", "Delete task successfully!");
    }
}
