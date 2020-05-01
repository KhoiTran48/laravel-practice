<?php

use App\User;
use App\Admin;
use Carbon\Carbon;
use App\Events\TaskEvent;
use App\Jobs\SendEmailJob;
use App\Mail\SendEmailMailable;
use Spatie\Permission\Models\Role;
use App\Notifications\TaskCompleted;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;

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

Route::post("/upload_by_vue", "HomeController@uploadByVue")->name("upload_by_vue");

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

use App\Http\Services\TestServiceInterface;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Notifications\AdminResetPasswordNotification;

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

Route::get("create-role", function(){
    Role::create(["name" => "writer"]);
});

Route::get("create-permission", function(){
    Permission::create(["name" => "write post"]);
});

Route::get("give-permission", function(){
    $role = Role::find(1);
    $permission = Permission::find(1);
    $role->givePermissionTo($permission);
});

Route::get("remove-permission", function(){
    $role = Role::find(1);
    $permission = Permission::find(1);
    // $permission->removeRole($role);
    $role->revokePermissionTo($permission);
});

Route::get("give-permission-user", function(){
    auth()->user()->givePermissionTo("write post");
});

Route::get("assign-role-user", function(){
    auth()->user()->assignRole("writer");
});

Route::get("user-get-permission", function(){
    return auth()->user()->getAllPermissions();
    // return auth()->user()->getDirectPermissions();
    // return auth()->user()->getPermissionsViaRoles();
});

Route::get("user-get-role", function(){
    return auth()->user()->roles;
})->middleware("permission: edit post|write post");

Route::get("get-user-base-on-role", function(){
    return User::role("writer")->get();
})->middleware("role:writers|writer");

Route::get("/verify_otp", "VerifyOTPController@showVerifyForm");
Route::post("/verify_otp", "VerifyOTPController@verify")->name("verify_otp");
Route::post("/resend_otp", "VerifyOTPController@resendOTP")->name("resend_otp");

