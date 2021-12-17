<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = "quizzes";
    public $timestamps = false;
    protected $guarded = ['id'];

    public function content()
    {
        return $this->hasOne('App\Models\Content', 'id', 'content_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function questions()
    {
        return $this->hasMany('App\Models\QuizzesQuestion');
    }

    public function questionsGradeSum()
    {
        return $this->questions()->groupBy('quiz_id')
            ->selectRaw('quiz_id, cast(sum(grade) as unsigned) as grade_sum');
    }

    public function QuizResults()
    {
        return $this->hasMany('App\Models\QuizResult');
    }


}
