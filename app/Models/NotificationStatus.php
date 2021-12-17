<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationStatus extends Model
{
    protected $table = 'notification_status';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function notification(){
        return $this->belongsTo('App\Models\Notification','notification_id');
    }
}
