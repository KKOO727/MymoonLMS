<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellRate extends Model
{
    protected $table = 'sells_rate';
    protected $guarded = ['id'];
    public $timestamps = false;
}
