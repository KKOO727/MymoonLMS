<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $table = 'blog_category';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function posts(){
        return $this->hasMany('App\Models\Blog','category_id');
    }
}
