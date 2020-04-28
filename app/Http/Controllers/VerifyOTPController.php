<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OTPRequest;
use Illuminate\Support\Facades\Cache;

class VerifyOTPController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function verify(OTPRequest $request)
    {
        if(request('otp') == auth()->user()->OTP()){
            auth()->user()->update(['verified' => 1]);
            return redirect("/home");
        }
        return back()->withErrors("OTP is expired or invalid");
    }

    public function showVerifyForm()
    {
        return view("OTP.verify");
    }

    public function resendOTP(Request $request)
    {
        auth()->user()->sendOTP(request("otp_via"));
        return redirect("/verify_otp")->with("message", "Your OTP is sent, please check!");
    }

}
