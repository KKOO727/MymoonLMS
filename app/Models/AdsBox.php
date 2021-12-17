<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsBox extends Model
{
    protected $table = 'ads_box';
    protected $guarded = ['id'];
    public $timestamps = false;

    static $positions = [
        'main-slider-side' => 'homepage_slider',
        'main-article-side' => 'homepage_articles',
        'category-side' => 'cat_page_sidebar',
        'category-pagination-bottom' => 'cat_page_bottom',
        'product-page' => 'product_page',
    ];

    static $sizes = [
        'Original' => 'col-md-12 col-sm-12 col-xs-12',
        '1/2' => 'col-md-6 col-sm-6 col-xs-12',
        '1/3' => 'col-md-4 col-sm-6 col-xs-12',
        '1/4' => 'col-md-3 col-sm-6 col-xs-12',
    ];
}
