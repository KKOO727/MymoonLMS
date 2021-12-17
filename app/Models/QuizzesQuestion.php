<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizzesQuestion extends Model
{
    protected $table = "quizzes_questions";
    public $timestamps = false;
    protected $guarded = ['id'];

    public function questionsAnswers()
    {
        return $this->hasMany('App\Models\QuizzesQuestionsAnswer', 'question_id', 'id');
    }
}
