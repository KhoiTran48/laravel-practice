<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post("/upload", "HomeController@upload")->name("upload");

Route::get("/todo", "TodoController@index")->name("todo.list");

Route::get("/todo/create", "TodoController@create")->name("todo.create");
Route::post("/todo/store", "TodoController@store")->name("todo.store");

Route::get("/todo/edit/{id}", "TodoController@edit")->name("todo.edit");
Route::post("/todo/update/{id}", "TodoController@update")->name("todo.update");

Route::get("/todo/delete/{id}", "TodoController@destroy")->name("todo.delete");

