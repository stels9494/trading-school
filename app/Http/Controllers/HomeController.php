<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $user = auth()->user();
        if ($user->hasRole('admin')){
            return redirect()->route('admin.index');
        }
        return view('home');
    }

    public function test(){
        foreach (User::where('id', '>', 1)->get() as $user){
            broadcast(new \App\Events\StartGame($user, $user));
        }
    }
}
