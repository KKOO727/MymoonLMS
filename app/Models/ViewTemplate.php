<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewTemplate extends Model
{
    protected $table = 'view_templates';
    public $timestamps = false;
    protected $guarded = ['id'];
}
