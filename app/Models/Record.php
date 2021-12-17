<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'record';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function category(){
        return $this->belongsTo('App\Models\ContentCategory','category_id');
    }
    public function content(){
        return $this->belongsTo('App\Models\Content','content_id');
    }
    public function fans(){
        return $this->hasMany('App\Models\RecordFans','record_id');
    }
}
