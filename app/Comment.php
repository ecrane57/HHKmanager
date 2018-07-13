<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	
	protected $appends = ['timeago'];
	
    public function author(){
	    return $this->belongsTo('App\User', 'user_id')->withTrashed();
    }
    
    public function site(){
	    return $this->belongsTo('App\Site');
    }
    
    public function getTimeagoAttribute(){
	    return $this->created_at->diffForHumans();
    }
}
