<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    protected $guarded = ['id'];
    protected $table = 'request';
    public $timestamps = false;

    public function fans(){
        return $this->hasMany('App\Models\RequestFans','request_id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function requester(){
        return $this->belongsTo('App\User','requester_id');
    }
    public function category(){
        return $this->belongsTo('App\Models\ContentCategory','category_id');
    }
    public function content(){
        return $this->belongsTo('App\Models\Content','content_id');
    }
    public function suggestions(){
        return $this->hasMany('App\Models\RequestSuggestion','request_id');
    }
}
