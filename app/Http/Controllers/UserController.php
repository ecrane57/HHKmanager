<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\User;
use App\Role;
use Session;
use Carbon\Carbon;

class UserController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->hasAnyRole("Admin")){
	        $users = User::all();
	        $roles = Role::all();
	        return view('users.index')->with(['users'=>$users, 'roles'=>$roles]);
        }else{
	        return abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    if($request->email){
		    $request->email .= "@nonprofitsoftwarecorp.org";
	    }
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('users.index', [])
                ->withErrors($validator)
                ->withInput();
        }else{
        	$user = new User;
        
        	$user->first_name = $request->first_name;
        	$user->last_name = $request->last_name;
        	$user->email = $request->email;
        	$user->password = bcrypt($request->password);
        	if($request->reset){
	        	$user->temp_password = Carbon::now();
        	}
        	$user->save();
        
        	$user->roles()->sync($request->roles, false);
        
        	Session::flash('success', 'New User has been created!');
        	
        	return redirect()->route('users.index', []);
        }

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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    
        public function resetpassword(Request $request, $id)
    {
	    $currentUser = Auth::user();
	    if($id == $currentUser->id || $currentUser->hasAnyRole('Admin')){
		    
	        $validator = Validator::make($request->all(), [
	            'password' => 'required|string|min:8|confirmed',
	        ]);
	        
	        if ($validator->fails()) {
	            return redirect()->back()
	                ->withErrors($validator)
	                ->withInput();
	        }else{
	        	$user = User::find($id);

	        	$user->password = bcrypt($request->password);
		        $user->temp_password = null;
		        
	        	$user->save();
	        	        
	        	Session::flash('success', 'Password saved!');
	        	
	        	return redirect()->back();
	        }
        }else{
	        return abort(403);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
