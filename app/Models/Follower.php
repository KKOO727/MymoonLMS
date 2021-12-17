<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    protected $table = 'follow';
    protected $guarded = ['id'];
    public $timestamps = false;
}
