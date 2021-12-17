<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
