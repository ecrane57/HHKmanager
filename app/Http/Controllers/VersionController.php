<?php

namespace App\Http\Controllers;

use App\Version;
use Session;
use Illuminate\Http\Request;
use App\Rules\fileExists;
use Storage;
use Auth;

class VersionController extends Controller
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
	    if(Auth::user()->hasAnyRole("Admin")){
        	$versions = Version::orderBy('release_date', 'desc')->withTrashed()->get();
			return view('versions.index')->with(['versions'=>$versions]);
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
	    if(Auth::user()->hasAnyRole("Admin")){
	        $request->validate(array(
		        'name' => 'required|max:255|unique:versions',
		        'path' => ['required', new fileExists("hhk")],
		        'releaseDate' => 'required|date'
	        ));
	        if($request->patch == NULL){
		        $request->patch = false;
	        }elseif($request->patch == "on"){
		        $request->patch = true;
	        }
	        
	        $releaseNotes = "";
	        if(Storage::disk("hhk")->exists($request->path . "/release_notes.txt")){
		        $releaseNotes = Storage::disk("hhk")->get($request->path . "/release_notes.txt");
		        $releaseNotes = str_replace(["\r\n", "\r", "\n"], '<br>', $releaseNotes);
	        }
	        
	        $version = new Version;
	        
	        $version->name = $request->name;
	        $version->filepath = $request->path;
	        $version->patch = $request->patch;
	        $version->release_date = $request->releaseDate;
	        $version->release_notes = $releaseNotes;
	        $version->save();
	        
	        Session::flash('success', 'New Version added');
	        
	        return redirect()->route('versions.index');
	    }else{
		    return abort(403);
	    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Version  $version
     * @return \Illuminate\Http\Response
     */
    public function show(Version $version)
    {
        //
    }
    
    public function showJson($id)
    {
	    $version = Version::find($id);
	    return response()->json($version);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Version  $version
     * @return \Illuminate\Http\Response
     */
    public function edit(Version $version)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Version  $version
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Version $version)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Version  $version
     * @return \Illuminate\Http\Response
     */
    public function destroy(Version $version)
    {
	    if(Auth::user()->hasAnyRole("Admin")){
	        $version->delete();
	        $version->save();
	        return response()->json($version->trashed());
	    }else{
		    return abort(403);
	    }
    }
    
    public function restore($id)
    {
	    if(Auth::user()->hasAnyRole("Admin")){
		    $version = Version::onlyTrashed()->find($id);
	        $version->restore();
	        $version->save();
	        return response()->json($version->trashed());
	    }else{
		    return abort(403);
	    }
    }
}
