<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketsUser extends Model
{
    protected $table = 'tickets_user';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
