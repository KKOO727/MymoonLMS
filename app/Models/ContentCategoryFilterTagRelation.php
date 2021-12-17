<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentCategoryFilterTagRelation extends Model
{
    protected $table = 'contents_category_filter_tag_relation';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function tag(){
        return $this->belongsTo('App\Models\ContentCategoryFilterTag','tag_id');
    }
}
