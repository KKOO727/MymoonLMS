<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleRate extends Model
{
    protected $table = 'article_rate';
    protected $guarded = ['id'];
    public $timestamps = false;
}
