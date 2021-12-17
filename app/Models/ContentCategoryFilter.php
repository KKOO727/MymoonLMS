<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentCategoryFilter extends Model
{
    protected $guarded = ['id'];
    protected $table = 'contents_category_filter';
    public $timestamps = false;

    public function category(){
        return $this->belongsTo('App\Models\ContentCategory','category_id');
    }

    public function tags(){
        return $this->hasMany('App\Models\ContentCategoryFilterTag','filter_id');
    }
}
