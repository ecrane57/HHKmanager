<?php

namespace App\Http\Controllers;

use App\Site;
use App\Comment;
use App\Version;
use Illuminate\Http\Request;
use Storage;
use Session;
use Auth;
use App\Rules\fileNotExist;
use App\Rules\fileExists;
use Config_Lite;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use DB;

class SiteController extends Controller
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
        //
    }
    
    public function demo()
    {
	    $sites = Site::all();
	    $sites = $sites->where('config.site.Mode', 'demo');
	    $versions = Version::orderBy('release_date', 'desc')->get();
	    $mode = "Demo";
	    
        return view('sites.index')->with(['sites'=>$sites, 'versions'=>$versions, 'mode'=>$mode]);
    }
    
    public function live()
    {
        $sites = Site::all();
        if(count($sites->whereNotIn('config.site.Mode', ['demo', 'live'])) > 0){
		    Session::flash("info", "There is one or more sites with issues, <a href='" . route("sites.other") . "'>Click here to fix</a>");
	    }
        $sites = $sites->where('config.site.Mode', 'live');
	    $versions = Version::orderBy('release_date', 'desc')->get();
	    $mode = "Live";
	    
        return view('sites.index')->with(['sites'=>$sites, 'versions'=>$versions, 'mode'=>$mode]);
    }
    
    public function other()
    {
	    $sites = Site::all();
	    $sites = $sites->whereNotIn('config.site.Mode', ['demo', 'live']);
	    $versions = Version::orderBy('release_date', 'desc')->get();
	    $mode = "Other";
	    
        return view('sites.index')->with(['sites'=>$sites, 'versions'=>$versions, 'mode'=>$mode]);
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
        $request->validate(array(
		        'name' => 'required|max:255|unique:sites',
		        'path' => ['required', new fileNotExist("prod")],
		        'version' =>['required', "exists:versions,id"],
	        ));
	        $version = Version::find($request->version);
	        $patharray = explode('/', $request->path);
	        $dbname = end($patharray);
	        $dbpassword = str_random(40);
	        
			$output = "";
			
			try{
				passthru(base_path() . "/scripts/newSite.sh " . $version->filepath . " " . $request->path);
			}catch(\Exception $e){
				Session::flash('error', "Failed to copy files: " . $e->getMessage());
				return redirect()->back();
			}
	
			if(Storage::disk('prod')->exists($request->path . "/conf/site.cfg")){
				$config = new Config_Lite(Storage::disk('prod')->path($request->path . "/conf/site.cfg"));
				$config->set('db', 'URL', '10.138.0.21')
						->set('db', 'User', $dbname)
						->set('db', 'Password', self::hhkencrypt($dbpassword))
						->set('db', 'Schema', $dbname);
						
				if($request->demo){
					$config->set('site', 'Mode', 'demo');
				}else{
					$config->set('site', 'Mode', 'live');
				}
						
				$config->save();
			}else{
				Session::flash('error', "Unable to find site.cfg - more info below<br><pre>" . $output . "</pre>");
				return redirect()->back();
			}
			
			$schemaoutput = DB::select('CALL create_user_schema(?, ?)', [$dbname,$dbpassword]);
			
			if(isset($schemaoutput[0]->error)){
				Session::flash('error', "Error creating database, " . $schemaoutput[0]->error);
				return redirect()->back();
			}
			
			if(isset($schemaoutput[0]->success)){
				$output .= "Schema created successfully<br>";
			}
			
	        $site = new Site;
	        
	        $site->name = $request->name;
	        $site->url = $request->path;
	        $site->save();
	        
	        $comment = new Comment;
	        $comment->body = "Site created";
	        $comment->author()->associate(Auth::user());
	        $comment->save();
	        
	        $site->comments()->save($comment);
	        
	        $output .= '<br>New Schema Username: ' . $dbname . '<br>New Schema password: ' . $dbpassword . '<br>Site created successfully, <a href="https://hospitalityhousekeeper.net/' . $request->path . '/install/step2.php" target="_blank">click here</a> to continue installation.<br>';
	        
	        Session::flash('success', "<pre>" . $output . "</pre>");
	        
	        return redirect()->back();

    }
    
    public function hhkencrypt($password){
	    
	    $key = "017d609a4b2d8910685595C8df";
	    $key = hash('sha256', $key);
	    $iv = "fYfhHeDmf j98UUy4";
	    $iv = substr(hash('sha256', $iv), 0, 16);
	    $encrypt_method = "AES-256-CBC";
	    $output = false;
	    
	    $output = base64_encode(openssl_encrypt($password, $encrypt_method, $key, 0, $iv));
	    //openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	    return $output;
    }
    
    public function import(Request $request)
    {
        $request->validate(array(
		        'path' => ['required', 'unique:sites,url', new fileExists("prod")],
	        ));
	        
	        $conf = "";
	        $site_name = "";
	        $version = "";
	        try{
		        		        
		        $site = new Site;
		        
		        $site->url = $request->path;
		        $site->save();
		        
		        $comment = new Comment;
		        $comment->body = "Site added to dashboard";
		        $comment->author()->associate(Auth::user());
		        $comment->save();
		        
		        $site->comments()->save($comment);		        
		        
		        Session::flash('success', 'New Site added');
	        }catch(\Exception $e){
		        Session::flash('error', $e->getMessage());
	        }
	        
	        return redirect()->route('sites.live');
    }
    
    public function upgrade(Request $request)
    {
	    
	    $request->validate(array(
	    	'version_id' => ['required','exists:versions,id'],
		    'site_id' => ['required','exists:sites,id'],
		    'user' => ['required'],
		    'pw' => ['required'],
	    ));
	    try{
		    
		    $version = Version::find($request->version_id);
		    $site = Site::find($request->site_id);
		    $script = "bash " . base_path() . "/scripts/upgrade.sh " . $version->filepath . " " . $site->url;
		    $upgradeURL = "https://hospitalityhousekeeper.net/" . $site->url . "/admin/ws_update.php";
		    $client = new Client(); //GuzzleHttp\Client
		    
		    //Check password
		    $passwordResult = $client->request('GET', $upgradeURL, [
				'query' => [
					'cd' => $site->config['db']['Schema'],
					'un' => $request->user,
					'so' => md5($request->pw),
					'ck' => 'y'
				]
			]);
			
			$passwordJson = json_decode($passwordResult->getBody());
			
			if(isset($passwordJson->resultMsg) && $passwordJson->resultMsg == "bubbly"){
		    
			    //copy files
			    ob_start();
			    passthru($script);
				$scriptoutput = ob_get_contents();
				ob_end_clean();
				
			    //run update script
				$result = $client->request('GET', $upgradeURL, [
					'query' => [
						'cd' => $site->config['db']['Schema'],
						'un' => $request->user,
						'so' => md5($request->pw),
					]
				]);
				
				$json = json_decode($result->getBody());
				
				if(isset($json->error)){
					Session::flash('error', "<pre>" . $scriptoutput . "\nHHK response: \n" . $json->error .  "</pre>");	
				}elseif(isset($json->errorMsg) && $json->errorMsg != ""){
					Session::flash('error', "<pre>" . $scriptoutput . "\nHHK response: \n" . $json->errorMsg .  "</pre>");
				}elseif(isset($json->resultMsg)){
					Session::flash('success', "<pre>" . $scriptoutput . "\nHHK response: \n" . $json->resultMsg .  "</pre>");
				}else{
					Session::flash('error', "An unknown error occurred.<br>Possible solution: Does ws_update.php exist in the page table?<br>script results (if any) <br><pre>" . $scriptoutput . "</pre>"  . $site->config['db']['Schema']);
				}
				
			}else{
				Session::flash('error', "Unable to update - Invalid Password");
			}
	    }catch(\Exception $e){
		   	Session::flash("error", "The following error has occurred: \n" . $e->getMessage() . " Line: " . $e->getLine());
	    }
	    return redirect()->back();
    }
    
    
    public function setLive(Site $site)
    {
	    
	    $slug = explode("/", $site->url);
	    
	    $slugEnd = end($slug);
	    
	    if($slug[0] == "demo"){
	    	$script = "bash " . base_path() . "/scripts/move.sh " . $slugEnd;
			
			passthru($script);
			
	    	if(Storage::disk('prod')->exists($slugEnd . "/conf/site.cfg")){
				
				$site->url = $slugEnd;
				$site->save();
				session::flash('success', "Site " . $site->siteName . " moved successfully.<br><br><strong>Further tasks</strong><ul><li>Configure web server for $slugEnd.hospitalityhousekeeper.net</li></ul>");
			}else{
				session::flash('error', "The site could not be moved.");
			}
	    }else{
		    session::flash('error', "Site is already in the live directory");
	    }
	    
	    return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $site = Site::find($id);
        return response()->json($site);
    }
    
    public function getConfig($id){
	    $site = Site::find($id);
	    return response()->json($site->config);
    }
    
    public function getComments($id){
	    $site = Site::with('comments.author')->find($id);
	    return response()->json($site->comments);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Site $site)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Site $site)
    {
        $request->validate(array(
		        'name' => 'required|max:255',
		        'path' => 'required',
	        ));

	        
	        $site->name = $request->name;
	        $site->url = $request->path;
	        $site->save();
	        
	        $comment = new Comment;
	        $comment->body = "Site updated";
	        $comment->author()->associate(Auth::user());
	        $comment->save();
	        
	        $site->comments()->save($comment);
	        	        
	        Session::flash('success', $site->name . ' updated successfully');
	        
	        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
/*
        $script = "bash " . base_path() . "/scripts/delete.sh " . $site->url;
	    $scriptoutput = passthru($script);
	    if(!$scriptoutput == null){
		    Session::flash("error", "Could not delete site: " . $scriptoutput);
	    }
*/
	    $site->delete();
	    session::flash('success', "Site removed from dashboard successfully - No files were deleted.");
    }
    
    public function convertNotes(Site $site, $noteType, $page)
    {
	    $skip = 1000*($page-1);
 	    try{
		    $notes = $site->schema->table('note')->select('idNote')->get();
	    
		    if($noteType != "resv" && $noteType != "visit" && $noteType != "psg"){
			    return response()->json("Error: invalid note type");
		    }else{
		    	if($noteType == "resv"){
			    	$entities = $site->schema
			    	->table('reservation')
			    	->select('reservation.idReservation', 'reservation.Notes', 'registration.idPsg')
			    	->join('registration', 'reservation.idRegistration', '=', 'registration.idRegistration')
			    	->whereNotNull("reservation.Notes")
			    	->groupBy('reservation.idReservation')
			    	->skip($skip)->take(1000)->get();
		    	}else if($noteType == "visit"){
			    	$entities = $site->schema
			    	->table('visit')
			    	->select('visit.idReservation', 'visit.Notes', 'registration.idPsg')
			    	->join('registration', 'visit.idRegistration', '=', 'registration.idRegistration')
			    	->whereNotNull("visit.Notes")
			    	->groupBy('visit.idVisit')
			    	->skip($skip)->take(1000)->get();
			    }else if($noteType == "psg"){
			    	$entities = $site->schema
			    	->table('psg')
			    	->select('psg.idPsg', 'psg.Notes')
			    	->whereNotNull("psg.Notes")
			    	->groupBy('psg.idPsg')
			    	->skip($skip)->take(1000)->get();
			    }
			    
			    //return response()->json($entities);
			    
			    $entityNotes = [];
			    $insertCount = 0;
			    $skipCount = 0;
			    $totalCount = 0;
			    foreach($entities as $entity){
				    $noteString = $entity->Notes;
				    
				    if($noteString != null && strlen($noteString) > 11){
					    //$noteString = explode(PHP_EOL, $noteString);
					    //return response()->json($noteString);
					    try{
					    $noteString = preg_split("/\r\n([0-9][0-9]-[0-9][0-9]-[0-9][0-9][0-9][0-9]), /", $noteString, null, PREG_SPLIT_DELIM_CAPTURE);
					    //return response()->json($noteString);
					    $noteStrings = [];
					    $notes = [];
					    $debug = "";
					    foreach($noteString as $key => $str){
						    try{
						    if(preg_match("/^[0-9][0-9]-[0-9][0-9]-[0-9][0-9][0-9][0-9]/", $str)){
								array_push($noteStrings, $str);
								$debug .= "date matched " . $str . "\r\n";
							}else if($str != null){
								end($noteStrings);
								$key = key($noteStrings);
								reset($noteStrings);
								$noteStrings[$key] .= ", " . $str;
							}
							}catch(\Exception $e){
								return response()->json(["error"=>"Could not parse note - " . $e->getMessage(), "noteString" => $noteString, "str" => $str, 'entity' => $entity, 'debug'=> $debug]);
							}
						}
						
						}catch(\exception $e){
							return response()->json(["error" => "Could not parse note - " . $e->getMessage(), "noteString"=>$noteString]);
						}
						
						foreach($noteStrings as $note){
							
							$split = explode(", ", $note, 2);
							$date = $split[0];
							$date = date_create_from_format('m-d-Y', $date);
							$date = $date->format('Y-m-d');
							
							if(starts_with($split[1], "visit") ){
								$split = $split[1];
								$split = explode(", ", $split, 3);
								if(starts_with($split[0], "visit") && starts_with($split[1], "room")){
									$visit = trim($split[0], "visit ");
									$room = trim($split[1], "room ");
									$split = $split[2];
								}else{
									$visit = false;
									$room = false;
									$split = $split[1];
								}
									
							}else{
								$visit = starts_with($split[1], "visit");
								$room = starts_with($split[1], "visit");
								$split = $split[1];
							}
							$split = explode(" - ", $split, 2);
							$username = $split[0];
							    
							if(array_key_exists(1, $split)){
							    $content = $split[1];
							}else{
							    $content = "";
							}
							
							if($room){
								$content = "Room: " . $room . " - " . $content;
							}
							    

							//insert note into DB
							$site->schema->beginTransaction();
						    $note = [
							    'User_Name' => trim($username, " "),
							    'Note_Type' => 'text',
							    'Title' => '',
							    'Note_Text' => $content,
							    'Last_Updated' => $date,
							    'Status' => 'a',
							    'Timestamp' => $date
						    ];
						    
						    $totalCount += 1;
						    
						    if($noteType == "resv" || $noteType == "visit"){						    
						    	//$exists = $site->schema->table('note')->where($note)->get();
							    $exists = $site->schema->table('note')->join('reservation_note', 'note.idNote', '=', 'reservation_note.Note_Id')->where($note)->where('reservation_note.Reservation_Id', '=', $entity->idReservation)->get();
							    
								$count = count($exists);
								if($count == 0){
									//insert into note table
								    $idNote = $site->schema->table('note')->insertGetId($note, 'idNote');
								    //$idNote = 0;
								    //insert into connector table
								    if($idNote > 0){
									    $site->schema->table('reservation_note')->insert([
										    'Reservation_Id' => $entity->idReservation,
										    'Note_Id' => $idNote
									    ]);
									    
									    $concatVisitNotes = $site->schema->table("sys_config")->select("key", "Value")->where("Key", "=", "ConcatVisitNotes")->limit(1)->get();
									    $concatVisitNotes = $concatVisitNotes[0]->Value;
									    
									    if($entity->idPsg && $concatVisitNotes == "true"){
										    $site->schema->table('psg_note')->insert([
											    'Psg_Id' => $entity->idPsg,
											    'Note_Id' => $idNote
										    ]);
										}
									    
									    $insertCount += 1;
								    }
								    array_push($notes, $note);
								}else{
									$skipCount += 1;
									array_push($notes, ["The following note already exists" => $exists]);
								}
							}else{
								$exists = $site->schema->table('note')->join('psg_note', 'note.idNote', '=', 'psg_note.Note_Id')->where($note)->where('psg_note.Psg_Id', '=', $entity->idPsg)->get();
							    
								$count = count($exists);
								if($count == 0){
									//insert into note table
								    $idNote = $site->schema->table('note')->insertGetId($note, 'idNote');
								    //$idNote = 0;
								    //insert into connector table
								    if($idNote > 0){
									    $site->schema->table('psg_note')->insert([
										    'Psg_Id' => $entity->idPsg,
										    'Note_Id' => $idNote
									    ]);
									    
									    $insertCount += 1;
								    }
								    array_push($notes, $note);
								}else{
									$skipCount += 1;
									array_push($notes, ["The following note already exists" => $exists]);
								}
							}
							
							$site->schema->commit();
						    
		
						}
						
						
						
						array_push($entityNotes, [
					    	'idPsg' => $entity->idPsg,
					    	'notes' => $notes
						]);
					}
					
			    }
			    
			    return response()->json(["Site" => $site->config['site']['Site_Name'], "Notes in this batch" => $totalCount, "Notes Inserted" => $insertCount, "Duplicates prevented" => $skipCount]);
			}

		}catch(\Illuminate\Database\QueryException $e){
			$site->schema->rollback();
			return response()->json(["error" => $e->getMessage()]);
		}

    }
}
