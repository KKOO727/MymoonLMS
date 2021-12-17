<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessagesThread extends Model
{
    protected $table = 'messages_thread';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->belongsTo('App\Models\Messages','message_id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
