<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Site;
use App\Version;

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
        $this->middleware('two_factor');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $sites = Site::all();
	    $versions = Version::orderBy('release_date', 'desc')->get();
	    
        return view('home')->with(['sites'=>$sites, 'versions'=>$versions]);
    }
}
