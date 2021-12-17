<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelVideo extends Model
{
    protected $table = 'user_channel_video';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function chanel(){
        return $this->belongsTo('App\Models\Channel','chanel_id');
    }
    public function content(){
        return $this->hasOne('App\Models\Content','id','content_id');
    }
}
