<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{

    protected $table = 'class_schedule';
    protected $guarded = ['id'];
    public $timestamps = false;
}
