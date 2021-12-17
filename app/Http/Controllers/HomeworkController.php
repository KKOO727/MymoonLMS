<?php

namespace App\Http\Controllers;
use App\Models\Balance;
use App\Models\ClassModel;
use App\Models\Content;
use App\Models\ContentPart;
use App\Models\Events;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Schedule;
use App\Models\Sell;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe;
use Illuminate\Support\Facades\Session;

class HomeworkController extends Controller
{
    public function __construct()
    {

    }

    public function homework(Request $request){
        $user = (auth()->check()) ? auth()->user() : false;

        $error = array();
        $events = array();

        if($user->type == 'Teacher') {
            $active_courses=Sell::where('seller_id',$user->id)->where('status','ongoing')->get();
            foreach($active_courses as $sell){
                $temp_events = ClassModel::where('course_id', $sell->content_id)->where('user_id', $user->id)->get();
                $events[$sell->content_id]=$temp_events;
            }

        }else {
            $active_courses=Sell::where('buyer_id',$user->id)->where('status','ongoing')->get();
            foreach($active_courses as $sell){
                $temp_events = ClassModel::where('course_id', $sell->content_id)->where('booking_user_id', $user->id)->get();
                $events[$sell->content_id]=$temp_events;
            }
        }

        $data['active_courses']=$active_courses;

        $data['profile'] = $user;
        $data['userMetas'] = get_all_user_meta($user->id);
        $data['schedule'] = $events;
        $data['user'] = Auth::user();


        if (count($error) > 0) {
            $data['error'] = $error;
            return view(getTemplate() . '.user.error', $data);
        } else {

            return view(getTemplate() . '.user.quizzes.homeworkIndex', $data);
        }
    }

    public function classHomework(Request $request){

        $class_id=$request->id;
        $data['class']= $class = ClassModel::where('id',$class_id)->first();
        $data['course']=Content::where('id',$class->course_id)->first();
        $user = auth()->user();
        if($user->type == 'Teacher'){
            $quizzes = Quiz::where('class_id',$class_id)
                ->with(['questionsGradeSum', 'content'])
                ->get();
        }else{
            $quizzes = Quiz::where('status', 'active')->where('class_id',$class_id)
                ->with(['questionsGradeSum', 'content'])
                ->get();
        }

        foreach ($quizzes as $quiz) {
            $quizResults = QuizResult::where('student_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->orderBy('id', 'desc')
                ->get();

            $quiz->result = $quizResults->first();
            $quiz->result_count = count($quizResults);

            $quiz->can_try = true;
            if ((isset($quiz->attempt) and count($quizResults) >= $quiz->attempt) or (!empty($quiz->result) and $quiz->result->status === 'pass')) {
                $quiz->can_try = false;
            }
        }

        $data['quizzes']=$quizzes;
        return view(getTemplate() . '.user.quizzes.list', $data);
    }




}
