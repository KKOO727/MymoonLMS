<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsPlan extends Model
{
    protected $table = 'ads_plan';
    protected $guarded = ['id'];
    public $timestamps = false;
}
