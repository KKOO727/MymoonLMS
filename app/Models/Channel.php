<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'user_channel';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function contents(){
        return $this->hasMany('App\Models\ChannelVideo','chanel_id','id');
    }
}
