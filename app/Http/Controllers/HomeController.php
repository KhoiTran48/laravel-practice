<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // auth()->user()->clearMediaCollection("avatar");
        $avatars = auth()->user()->getMedia("avatar");
        return view('home', compact("avatars"));
    }

    public function destroyMedia()
    {
        auth()->user()->clearMediaCollection("avatar");
    }

    public function upload(Request $req)
    {
        User::updateAvatar($req);
        return back()->with("status", "File uploaded!");
    }

}
