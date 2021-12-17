<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseFeedbackModel extends Model
{
    protected $table = 'course_feedback';
    protected $guarded = ['id'];
    public $timestamps = false;
}
