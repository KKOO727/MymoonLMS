<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blog_posts';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function comments(){
        return $this->hasMany('App\Models\BlogComments','post_id');
    }
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }
    public function category(){
        return $this->belongsTo('App\Models\BlogCategory','category_id');
    }
}
