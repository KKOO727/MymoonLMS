<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $table = 'messages';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function threads(){
        return $this->hasMany('App\Models\MessagesThread','message_id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
