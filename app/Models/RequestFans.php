<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestFans extends Model
{
    protected $guarded = ['id'];
    protected $table = 'request_fans';
    public $timestamps = false;
}
