<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizzesQuestionsAnswer extends Model
{
    protected $table = "quizzes_questions_answers";
    public $timestamps = false;
    protected $guarded = ['id'];
}
