<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;
use DB;
use Config;
use Carbon\Carbon;

class Site extends Model
{
	
	protected $appends = ['config','sysconfig', 'city', 'priceModel', 'roomCount', 'lastAccessed'];
	protected $casts = [
        'config' => 'collection',
    ];
	
    public function comments(){
	    return $this->hasMany('App\Comment')->orderBy('created_at', 'desc');
    }
    
    public function getConfigAttribute(){
	    return parse_ini_string(Storage::disk("prod")->get($this->url . "/conf/site.cfg"), true);
    }
    
    public function getSchemaAttribute(){
	    $defaultConnection = config('database.connections.mysql');
        $newConnection = $defaultConnection;
        $newConnection['database'] = $this->config['db']['Schema'];
        Config::set('database.connections.' . $this->config['db']['Schema'], $newConnection);
        
        return DB::connection($this->config['db']['Schema']);
    }
    
    public function getSysconfigAttribute(){
	    try{
	    	$result = $this->schema->table('sys_config')->select('Key', 'Value')->get()->keyBy('Key');
	    }catch(\Illuminate\Database\QueryException $e){
		    $result = false;
	    }
	    return $result;
    }
    
    public function getCityAttribute(){
	    $zipCode = $this->config['house']['Zip_Code'];
	    if($zipCode){
		    try{
	    		$result = $this->schema->table('postal_codes')->select('Zip_Code', 'City', 'State')->where('Zip_Code', '=', $zipCode)->first();
	    	}catch(\Illuminate\Database\QueryException $e){
		    	$result = false;
	    	}
	    }else{
		    $result =  false;
	    }
	    return $result;
    }
    
    public function getPriceModelAttribute(){
	    if($this->sysconfig){
	    	$priceModelCode = $this->sysconfig->get('RoomPriceModel')->Value;
	    }else{
		    $priceModelCode = false;
	    }
	    if($priceModelCode){
		    try{
	    		$priceModel =  $this->schema->table('gen_lookups')->select('Description')->where([['Table_name', '=', 'Price_Model'],['Code', '=', $priceModelCode]])->first();
				$result = $priceModel->Description;
	    	}catch(\Illuminate\Database\QueryException $e){
		    	$result = false;
	    	}
	    }else{
		    $result = false;
	    }
	    
	    return $result;
    }
    
    public function getRoomCountAttribute(){
	    try{
		    $result = $this->schema->table('room')->count();
	    }catch(\Illuminate\Database\QueryException $e){
		    $result = false;
	    }
	    return $result;
    }
    
    public function getLastAccessedAttribute(){
	    try{
		    $lastAccessed = $this->schema->table('w_user_log')->select('Username','Access_Date')->orderBy('Access_Date', 'desc')->first();
	    }catch(\Illuminate\Database\QueryException $e){
		    $lastAccessed = false;
	    }
	    if($lastAccessed){
		   	$timezone = $this->config['calendar']['TimeZone'];
		   	if(!$timezone){
				$timezone = "UTC";
		    }
	    	$lastAccessed->Access_Date = Carbon::parse($lastAccessed->Access_Date, $timezone);
			return $lastAccessed;
		}else{
			return false;
		}

    }
    
}
