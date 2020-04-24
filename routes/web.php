<?php

use App\User;
use App\Admin;
use Carbon\Carbon;
use App\Events\TaskEvent;
use App\Jobs\SendEmailJob;
use App\Mail\SendEmailMailable;
use App\Notifications\TaskCompleted;
use Illuminate\Support\Facades\Mail;
use App\Http\Services\TestServiceInterface;
use App\Notifications\AdminResetPasswordNotification;

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

// Auth::routes();
Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::post("/upload", "HomeController@upload")->name("upload");

Route::get("/todo", "TodoController@index")->name("todo.list");

Route::get("/todo/create", "TodoController@create")->name("todo.create");
Route::post("/todo/store", "TodoController@store")->name("todo.store");

Route::get("/todo/edit/{id}", "TodoController@edit")->name("todo.edit");
Route::post("/todo/update/{id}", "TodoController@update")->name("todo.update");

Route::get("/todo/delete/{id}", "TodoController@destroy")->name("todo.delete");

Route::get("sendEmail", function(){
    // return (new SendEmailMailable());
    // Mail::to("trandinhkhoi48@gmail.com")->send(new SendEmailMailable());
    SendEmailJob::dispatch();
    //     ->delay(Carbon::now()->addSeconds(5));
    // $job = (new SendEmailJob())->delay(Carbon::now()->addSeconds(10));
    // dispatch($job);
    echo "Email is sent properly";
});

Route::get("admins", function(){
    $admins = Admin::all();
    dd($admins);
});

Route::get("test", function(TestServiceInterface $test){
    $test->doSomething(); 
});

Route::get("verify_email_first", "Auth\RegisterController@verifyEmailFirst")->name("verify_email_first");
Route::get("verify/{email}/{verifyToken}", "Auth\RegisterController@sendEmailDone")->name("send_email_done");

Route::get("admin/home", "AdminController@index");
Route::get("admin/editor", "EditorController@index");
Route::get("admin/test", "EditorController@testMethod");

Route::get("admin/login", "Admin\LoginController@showLoginForm")->name("admin.login");
Route::post("admin/login", "Admin\LoginController@login");
Route::post("admin/logout", "Admin\LoginController@logout")->name("admin.logout");

Route::get("admin/password/reset", "Admin\ForgotPasswordController@showLinkRequestForm")->name("admin.password.request");
Route::post("admin/password/email", "Admin\ForgotPasswordController@sendResetLinkEmail")->name("admin.password.email");

Route::get("admin/password/reset/{token}", "Admin\ResetPasswordController@showResetForm")->name("admin.password.reset");
Route::post("admin/password/reset", "Admin\ResetPasswordController@reset")->name("admin.password.update");

Route::get("admin/register", "Admin\RegisterController@showRegistrationForm")->name("admin.register");
Route::post("admin/register", "Admin\RegisterController@register");

Route::get("event", function(){
    $admin = Admin::find(1);
    event(new TaskEvent($admin));
    // Mail::to("trandinhkhoi48@gmail.com")->send(new SendEmailMailable());
});

Route::get("admin-notify", function(){
    Admin::find(1)->notify(new TaskCompleted());
    echo 'done';
    // return (new TaskCompleted())->toMail();
});

Route::get("notify", function(){
    User::find(1)->notify(new EmailNotification());
    echo 'done';
});

use Stichoza\GoogleTranslate\GoogleTranslate;

Route::get("translate", function(){
    echo GoogleTranslate::trans('Hello again', 'vn', 'en');
});

Route::get("loading", function(){
    // $adminRoles = App\role_admin::with('role')->get();
    $adminRoles = App\role_admin::all()->load('role');

    foreach ($adminRoles as $adminRole) {
        echo $adminRole->role->name;
    }
});
