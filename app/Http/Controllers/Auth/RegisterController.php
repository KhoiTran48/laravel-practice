<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Mail\verifyEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verify_token' => Str::random(40)
        ]);
        // Session::flash('status', "Registered! but verify your email to activate");
        // $this->sendEmail($user);
        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));
        
        Session::flash('status', "Registered! but verify your email to activate");
        return redirect(route("login"));
    }

    public function sendEmail($user)
    {
        Mail::to($user["email"])->send(new verifyEmail($user));
    }

    public function verifyEmailFirst()
    {
        return view("email.verify_email_first");
    }

    public function sendEmailDone(Request $request, $email, $verifyToken)
    {
        $user = User::where(['email' => $email, 'verify_token' => $verifyToken])->first();
        if($user){
            User::where(['email' => $email, 'verify_token' => $verifyToken])->update(['verified' => 1, 'verify_token' => NULL]);
            $this->guard()->login($user);
            return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
        }else{
            return "User not found";
        }
    }

}
