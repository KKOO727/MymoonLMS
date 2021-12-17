<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeFeedbackModel extends Model
{
    public $timestamps = false;
    protected $table = 'home_feedback';
    protected $guarded = ['id'];


}
