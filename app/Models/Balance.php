<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    public $timestamps = false;
    protected $table = 'balance_log';
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function exporter(){
        return $this->belongsTo('App\User','exporter_id');
    }

}
