<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqsModel extends Model
{
    public $timestamps = false;
    protected $table = 'faqs';
    protected $guarded = ['id'];


}
