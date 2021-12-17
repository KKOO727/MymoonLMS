<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketsMsg extends Model
{
    protected $table = 'tickets_msg';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function ticket()
    {
        return $this->belongsTo('App\Models\Tickets','ticket_id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
