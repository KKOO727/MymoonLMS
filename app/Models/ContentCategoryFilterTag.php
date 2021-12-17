<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentCategoryFilterTag extends Model
{
    public $timestamps = false;
    protected $table = 'contents_category_filter_tag';
    protected $guarded = ['id'];

    public function filter(){
        return $this->belongsTo('App\Models\ContentCategoryFilter','filter_id');
    }

    public function contents(){
        return $this->belongsToMany('App\Models\Content','contents_category_filter_tag_relation','tag_id','content_id');
    }
}
