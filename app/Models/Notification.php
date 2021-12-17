<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = ['id'];
    protected $table = 'notification';
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }

    public function status(){
        return $this->hasMany('App\Models\NotificationStatus','notification_id');
    }
}
