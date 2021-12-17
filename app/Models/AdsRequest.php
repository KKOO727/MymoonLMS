<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsRequest extends Model
{
    protected $table = 'ads_request';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function plan(){
        return $this->belongsTo('App\Models\AdsPlan','plan_id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function content(){
        return $this->belongsTo('App\Models\Content','content_id');
    }
}
