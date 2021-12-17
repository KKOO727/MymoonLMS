<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelRequest extends Model
{
    protected $table = 'user_channel_request';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function channel(){
        return $this->belongsTo('App\Models\Channel','channel_id');
    }
}
