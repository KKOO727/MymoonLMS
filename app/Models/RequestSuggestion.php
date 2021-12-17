<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestSuggestion extends Model
{
    protected $table = 'request_suggestion';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function content(){
        return $this->belongsTo('App\Models\Content','content_id');
    }
}
