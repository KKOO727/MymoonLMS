<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentCategory extends Model
{
    protected $table = 'contents_category';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function contents(){
        return $this->belongsToMany('App\Models\Content','contents_category_relation','category_id','content_id');
    }
    public function childs(){
        return $this->hasMany('App\Models\ContentCategory','parent_id');
    }
    public function filters(){
        return $this->hasMany('App\Models\ContentCategoryFilter','category_id')->orderBy('sort');
    }
    public function parent(){
        return $this->belongsTo('App\Models\ContentCategory','parent_id');
    }
    public function discount(){
        return $this->hasOne('App\Models\DiscountContent','off_id')
            ->where('type','category')
            ->where('first_date','<',time())
            ->where('last_date','>',time())
            ->where('mode','publish')
            ->orderBy('id','DESC')
            ->limit(1);
    }
}
