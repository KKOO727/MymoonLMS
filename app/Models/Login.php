<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $table = 'login';
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
}
