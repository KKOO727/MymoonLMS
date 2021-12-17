<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClearedSchedule extends Model
{
    protected $table = 'schedule_cleared';
    protected $guarded = ['id'];
    public $timestamps = false;
}
