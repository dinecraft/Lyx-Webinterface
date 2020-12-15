<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

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
    public function redirect()
    {
		if(Auth()->user()->role == 2)
		{
			return redirect("/hoster/dashboard");
		}
		else if(Auth()->user()->role == 3)
		{
			return redirect("/admin/dashboard");
		}
		return redirect("/");
    }
	
	public function showDashboard()
	{
		return view("home");
	}
}
