<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogComments extends Model
{
    protected $table = 'blog_comments';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function post(){
        return $this->belongsTo('App\Models\Blog','post_id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function childs(){
        return $this->hasMany('App\Models\BlogComments','parent');
    }
}
