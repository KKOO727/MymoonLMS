<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentComment extends Model
{
    protected $table = 'contents_comment';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function childs(){
        return $this->hasMany('App\Models\ContentComment','parent');
    }
    public function content()
    {
        return $this->belongsTo('App\Models\Content','content_id');
    }
}

