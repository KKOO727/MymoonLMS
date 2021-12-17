<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\ContentPart;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Barryvdh\Debugbar\ServiceProvider;

class AppointmentController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $user = $request->input('user');
        if($user != null || $user != ''){
            $user_id=$user;
        }else{
            $user_id = Auth::user()->id;
        }

        $data = Schedule::where('user_id',$user_id)->get(['id','title','start', 'end','course_id','course_type','user_id','status']);
        $newData=array();

        foreach($data as $event){
            if($event->status == 'available'){
                $color='#932680';
                $display='';
            }else if($event->status == 'temp_booked'){
                $color='#0F9797';
                $display='';
            }else{
                $color='#444';
                $display='';
            }

            $newData[]=['id'=>$event->id,'title'=>$event->title,'start'=>$event->start,'end'=>$event->end,'course_id'=>$event->course_id,
            'color'=>$color,'display'=>$display
            ];

        }
        return Response::json($newData);
    }

    public function individualSchedule(Request $request)
    {
        $course_id = $request->input('course');

        $data = Schedule::where('course_id',$course_id)->get();
        $newData=array();

        foreach($data as $event){
            if($event->status == 'available'){
                $color='#932680';
                $display='background';
            }else if($event->status == 'temp_booked'){
                $color='#0F9797';
                $display='';
            }else{
                $color='#444';
                $display='';
            }

            $newData[]=['id'=>$event->id,'start'=>$event->start,'end'=>$event->end,'course_id'=>$event->course_id,
                'color'=>$color,'display'=>$display
            ];
        }
        return Response::json($newData);
    }



    public function create(Request $request): \Illuminate\Http\JsonResponse
    {


        $only_date=substr($request->start, 0, 10);
        $formatted_date=date('Y-m-d H:i:s',strtotime($only_date));
        $actual_day=date('D',strtotime($formatted_date));
        $event=array();

        $from=date('Y-m-d',strtotime($request->from));
        $to=date('Y-m-d',strtotime($request->to));

        $begin = new DateTime($from);
        $interval = DateInterval::createFromDateString('1 day');
        $end = new DateTime($to);

        $period = new DatePeriod($begin, $interval, $end);


        $startHour = strtotime(substr($request->start, -13,-8));
        $endHour = strtotime(substr($request->end, -13,-8));



        foreach ($period as $dt) {

            $current_day=$dt->format("D");
            if($actual_day == $current_day){

                $j=0;
                for ($i=$startHour; $i<$endHour; $i = $i + 30*60) {

                    $start=$dt->format("Y-m-d").'T'.date("H:i:s", $i).'Z';
                    $end=$dt->format("Y-m-d").'T'.date("H:i:s", ($i+ 30*60)).'Z';
                    $insertArr = [ 'title' => $request->title,
                        'start' => $start,
                        'end' => $end,
                        'course_id' => $request->input('course'),
                        'course_type' => $request->input('course_type'),
                        'user_id' => $request->input('user_id'),
                        'status' => 'available',
                        'timeval' => strtotime($formatted_date),
                    ];

                    $check = Schedule::where('start',$start)
                        ->where('user_id', $request->input('user_id'))
                        ->get()->count();
                    if($check == 0){
                        $event[] = Schedule::insertGetId($insertArr);
                    }
                }
            }
        }
        return Response::json($event);
    }

    public function book(Request $request){
        $course_id=$request->course;
        $start=substr($request->start,0,19).'Z';
        $end=substr($request->end,0,19).'Z';


        $plan_id=$request->plan;
        $user=Auth::user();
        $user_id=$user->id;
        $content=Content::where('id',$course_id)->first();

        if($user->type =='Teacher'){
            $message='You cant buy any course.Please create a student account to book a course';
            return $message;
        }

        $check_event = Schedule::where('course_id', $course_id)
            ->where('start',$start)
            ->where('end',$end)
            ->get();
        if($check_event->count() == 1){
            if($check_event[0]->status=='available') {
                $plan = ContentPart::where('id', $plan_id)->first();
                $class_count = $plan->class_number;

                $book_count = Schedule::where('course_id', $course_id)
                    ->where('part_id', $plan_id)
                    ->where('booking_user_id', $user_id)
                    ->where('status', 'temp_booked')
                    ->get();

                $start_schedule=[];
                $counter=0;
                foreach($book_count as $row){
                    $only_date=substr($row->start, 0, 10);
                    $formatted_date=date('Y-m-d H:i:s',strtotime($only_date));
                    $actual_day=date('D',strtotime($formatted_date));
                    $startHourTemp = $actual_day.'-'.substr($row->start, -9,-1);
                    if(!in_array($startHourTemp,$start_schedule)){
                        $start_schedule[]=$startHourTemp;
                    }
                }
                $start_schedule_unique=array_unique($start_schedule);
                $counter=count($start_schedule_unique);

                if ($counter < $class_count) {

                    $data['status'] = 'temp_booked';
                    $data['booking_user_id'] = $user_id;
                    $data['part_id'] = $plan_id;

                    $only_date=substr($start, 0, 10);
                    $formatted_date=date('Y-m-d H:i:s',strtotime($only_date));
                    $actual_day=date('D',strtotime($formatted_date));
                    $event=array();

                    $from=date('Y-m-d',strtotime($content->date_from));
                    $to=date('Y-m-d',strtotime($content->date_to));

                    $date_from = new DateTime($from);
                    $interval = DateInterval::createFromDateString('1 day');
                    $date_to = new DateTime($to);

                    $period = new DatePeriod($date_from, $interval, $date_to);
                    $startHour = substr($start, -9,-1);
                    $endHour = substr($end, -9,-1);
                    foreach ($period as $dt) {

                        $current_day = $dt->format("D");
                        if ($actual_day == $current_day) {
                            $start = $dt->format("Y-m-d") . 'T' . $startHour . 'Z';
                            $end = $dt->format("Y-m-d") . 'T' . $endHour . 'Z';

                                $check = Schedule::where('start', $start)
                                    ->where('end', $end)
                                    ->where('course_id', $course_id)
                                    ->where('status', 'available')
                                    ->get()->count();

                                if ($check == 1) {
                                    $up = Schedule::where('start', $start)
                                        ->where('end', $end)
                                        ->where('course_id', $course_id)
                                        ->where('status', 'available')
                                        ->update($data);
                                    echo $up;
                                }
                        }
                    }
                    return true;
                } else {
                    return 'exceeded';
                }
            }else{
                return 'not available';
            }
        }else{
            return 'not found';
        }
    }

    public function deletePlanBooking(Request $request){

        $course_id=$request->course_id;
        $part_id=$request->plan_id;
        $user_id=Auth::user()->id;
        $id=$request->id;

        $check_event = Schedule::where('id',$id)
            ->where('booking_user_id',$user_id)
            ->where('course_id',$course_id)
            ->where('part_id',$part_id)
            ->where('status','temp_booked')
            ->get();
        $content=Content::where('id',$course_id)->first();

        if($check_event->count() == 1){
            $check_event=$check_event[0];
            $data['status'] = 'available';
            $data['booking_user_id'] = NULL;
            $data['part_id'] = NULL;

            $only_date=substr($check_event->start, 0, 10);
            $formatted_date=date('Y-m-d H:i:s',strtotime($only_date));
            $actual_day=date('D',strtotime($formatted_date));
            $event=array();

            $from=date('Y-m-d',strtotime($content->date_from));
            $to=date('Y-m-d',strtotime($content->date_to));

            $date_from = new DateTime($from);
            $interval = DateInterval::createFromDateString('1 day');
            $date_to = new DateTime($to);

            $period = new DatePeriod($date_from, $interval, $date_to);
            $startHour = substr($check_event->start, -9,-1);
            $endHour = substr($check_event->end, -9,-1);
            foreach ($period as $dt) {

                $current_day = $dt->format("D");
                if ($actual_day == $current_day) {
                    $start = $dt->format("Y-m-d") . 'T' . $startHour . 'Z';
                    $end = $dt->format("Y-m-d") . 'T' . $endHour . 'Z';

                    $check = Schedule::where('start', $start)
                        ->where('end', $end)
                        ->where('course_id', $course_id)
                        ->where('status', 'temp_booked')
                        ->get()->count();
                    if ($check == 1) {
                        $up = Schedule::where('start', $start)
                            ->where('end', $end)
                            ->where('course_id', $course_id)
                            ->where('status', 'temp_booked')
                            ->update($data);
                        //echo $up;
                    }
                }
            }
            return true;
        }else{
            return 'failed';
        }
    }


    public function unselect(Request $request){

        $course_id=$request->course_id;
        $part_id=$request->plan_id;
        $user_id=Auth::user()->id;
        $id=$request->id;

        $check_event = Schedule::where('id',$id)
            ->where('booking_user_id',$user_id)
            ->where('course_id',$course_id)
            ->where('part_id',$part_id)
            ->where('status','temp_booked')
            ->get();

        if($check_event->count() == 1){
            $complete_booking = Schedule::where('id', $id)
                        ->where('status', 'temp_booked')
                        ->update(['status'=>'available']);

            $book_count = Schedule::where('course_id', $course_id)
                ->where('part_id', $part_id)
                ->where('booking_user_id', $user_id)
                ->where('status', 'temp_booked')
                ->get()->count();

                    return $book_count;

            }else{
                return 'failed';
            }
    }

    public function bookSingleTeacher(Request $request){

        $course_id=$request->course;
        $start=substr($request->start,0,19).'Z';
        $end=substr($request->end,0,19).'Z';
        $plan_id=$request->plan;
        $user=Auth::user();
        $user_id=$user->id;
        $content=Content::where('id',$course_id)->first();

        if($user->type =='Teacher'){
            $message='You cant buy any course.Please create a student account to book a course';
            return $message;
        }

        $check_event = Schedule::where('course_id', $course_id)
            ->where('start',$start)
            ->where('end',$end)
            ->get();
        if($check_event->count() == 1){
            if($check_event[0]->status=='available') {
                $plan = ContentPart::where('id', $plan_id)->first();
                    $data['status'] = 'temp_booked';
                    $data['booking_user_id'] = $user_id;
                    $data['part_id'] = $plan_id;
                    $complete_booking = Schedule::where('course_id', $course_id)
                        ->where('status', 'available')
                        ->where('start', $start)
                        ->where('end', $end)
                        ->update($data);
                $book_count = Schedule::where('course_id', $course_id)
                    ->where('part_id', $plan_id)
                    ->where('booking_user_id', $user_id)
                    ->where('status', 'temp_booked')
                    ->get()->count();

                    return $book_count;

            }else{
                return 'not available';
            }
        }else{
            return 'not found';
        }

    }

    public function removeSchedule(Request $request){
        $user=Auth::user();
        $clear_event = Schedule::where('user_id', $user->id)
            ->where('status','available')
            ->delete();
    }


    public function removeSingleSchedule(Request $request){
        $user=Auth::user();
        $schedule_id = $request->input('id');
        $event = Schedule::where('id', $schedule_id)->where('user_id',$user->id)->whereNotIn('status',array('booked'))->delete();
        return $event;
    }


}
