<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentCategoryRelation extends Model
{
    protected $table = 'contents_category_relation';
    public $timestamps = false;

    public function contents(){
        return $this->hasMany('App\Models\Content','content_id');
    }
    public function categories(){
        return $this->hasMany('App\Models\ContentCategory','category_id');
    }
}
