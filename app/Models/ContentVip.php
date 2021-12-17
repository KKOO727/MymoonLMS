<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentVip extends Model
{
    protected $table = 'contents_vip';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function content(){
        return $this->hasOne('App\Models\Content','id','content_id');
    }
}
