<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = 'contents';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\ContentCategory', 'category_id');
    }

    public function metas()
    {
        return $this->hasMany('App\Models\ContentMeta', 'content_id');
    }

    public function parts()
    {
        return $this->hasMany('App\Models\ContentPart', 'content_id')->orderBy('sort', 'ASC');
    }

    public function partsactive()
    {
        return $this->hasMany('App\Models\ContentPart', 'content_id')->where('mode', 'publish');
    }

    public function partsRequest()
    {
        return $this->hasMany('App\Models\ContentPart', 'content_id')->where('mode', 'request');
    }

    public function sells()
    {
        return $this->hasMany('App\Models\Sell', 'content_id');
    }

    public function favorite()
    {
        return $this->hasMany('App\Models\Favorite', 'content_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\ContentComment', 'content_id')->orderBy('id', 'DESC');
    }

    public function supports()
    {
        return $this->hasMany('App\Models\ContentSupport', 'content_id');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'content_id');
    }

    public function tags()
    {
        return $this->hasMany('App\Models\ContentCategoryFilterTagRelation', 'content_id');
    }

    public function discount()
    {
        return $this->hasOne('App\Models\DiscountContent', 'off_id')->where('type', 'content')->where('last_date', '>', time())->orderBy('id', 'DESC');
    }

    public function vip()
    {
        return $this->hasMany('App\Models\ContentVip', 'content_id');
    }

    public function rates()
    {
        return $this->hasMany('App\Models\ContentRate', 'content_id');
    }

    public function off()
    {
        return $this->hasMany('App\Models\DiscountContent', 'id', 'off_id');
    }

    public function filters()
    {
        return $this->belongsToMany('App\Models\ContentCategoryFilterTag', 'contents_category_filter_tag_relation', 'content_id', 'tag_id');
    }

    public function usage()
    {
        return $this->hasMany('App\Models\Usage', 'id', 'product_id');
    }

    public function viewer()
    {
        return $this->hasMany('App\Models\Usage', 'user_id');
    }

    public function quizzes()
    {
        return $this->hasMany('App\Models\Quiz');
    }

    public function meetings(){
        return $this->hasMany('App\Models\MeetingDate');
    }
}
