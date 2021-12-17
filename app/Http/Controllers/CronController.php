<?php

namespace App\Http\Controllers;


use App\Models\ClassModel;
use App\Models\ClearedSchedule;
use App\Models\Content;
use App\Models\Schedule;
use App\Models\Sell;
use Illuminate\Foundation\Auth\User;

class CronController extends Controller
{
    public function __construct()
    {
    }


    public function cronClearTempBooking()
    {
        $temp_bookings = Schedule::where('status', 'temp_booked')->get();

        if ($temp_bookings->count() > 0) {
            foreach ($temp_bookings as $booking) {
                $booking_time = strtotime($booking->updated_at);
                $nowtime = strtotime(date('Y-m-d H:i:s'));
                $interval = $nowtime - $booking_time;
                if ($interval >= 1800) {
                    $log = ClearedSchedule::create(['schedule_id' => $booking->id, 'user_id' => $booking->booking_user_id, 'booked_at' => $booking->updated_at]);
                    $clear_schedule = Schedule::where('id', $booking->id)->update(['status' => 'available', 'booking_user_id' => null]);
                    return 'success';
                }
            }
        }
    }

    public function cronClearContent()
    {
        $contents = Content::where('mode', 'publish')->whereIn('type', ['1', '2'])->get();
        if ($contents->count() > 0) {
            foreach ($contents as $content) {
                if ($content->date_to != null || $content->date_to != '') {
                    $parts = explode('-', $content->date_to);
                    if (count($parts) == 3) {
                        $date_to = $parts[2] . '-' . $parts[1] . '-' . $parts[0] . ' 23:59:59';
                        $date_exp = strtotime($date_to);
                        $nowtime = strtotime(date('Y-m-d'));
                        if ($nowtime > $date_exp) {
                            $expire = Content::where('id', $content->id)->update(['mode' => 'expired']);
                            return 'success';
                        }
                    }
                }
            }
        }
    }

    public function cronDeactivateTeacher()
    {
        $teachers = User::where('type', 'Teacher')->where('mode', 'active')->get();
        if ($teachers->count() > 0) {
            foreach ($teachers as $teacher) {
                $active = false;
                $videos = Content::where('user_id', $teacher->id)->where('type', '3')->where('mode', 'publish')->get();
                if ($videos->count() > 0) {
                    $active = true;
                } else {
                    $groups = Content::where('user_id', $teacher->id)->where('type', '2')->where('mode', 'publish')->get();
                    if ($groups->count() > 0) {
                        $active = true;
                    } else {
                        $one2one = Content::where('user_id', $teacher->id)->where('type', '1')->where('mode', 'publish')->get();
                        if ($one2one->count() > 0) {
                            $today = date('Y-m-d');
                            $schedules = Schedule::where('course_id', $one2one[0]->id)->get();
                            foreach ($schedules as $sch) {
                                $start = substr($sch->start, 0, 10);
                                if (strtotime($today) <= strtotime($start)) {
                                    $active = true;
                                    break;
                                }
                            }
                        }
                    }
                }
                if (!$active) {
                    $deactivate_teacher = User::where('id', $teacher->id)->update(['mode' => 'deactive']);
                }
            }
        }
    }

    public function cronRenewPlan()
    {

        $sells = Sell::where('type', 'recurring')->where('status', 'ongoing')->get();
        if ($sells->count() > 0) {
            foreach ($sells as $sell) {
                $classes = ClassModel::where('course_id', $sell->content_id)
                    ->where('part_id', $sell->content_part_id)
                    ->where('user_id', $sell->seller_id)
                    ->where('booking_user_id', $sell->buyer_id)
                    ->where('completed', '0')
                    ->get();
                if ($classes->count() > 0) {
                    $last_cyle = strtotime(date('Y-m-d', $sell->created_at));
                    $next_cycle = date("Y-m-d", strtotime("+1 month", $last_cyle));
                    $today = date('Y-m-d');

                    //echo date('Y-m-d',$sell->created_at).'<br>';
                    //echo $next_cycle.'<br>';
                    //dd($sell);

                    if (strtotime($today) >= strtotime($next_cycle)) {
                        $update = Sell::where('id', $sell->id)->update(['status' => 'completed']);
                        $data = [
                            'seller_id' => $sell->seller_id,
                            'buyer_id' => $sell->buyer_id,
                            'content_id' => $sell->content_id,
                            'content_part_id' => $sell->content_part_id,
                            'transaction_id' => $sell->transaction_id,
                            'type' => $sell->type,
                            'mode' => $sell->mode,
                            'post_code' => $sell->post_code,
                            'post_code_date' => $sell->post_code_date,
                            'post_confirm' => $sell->post_confirm,
                            'post_feedback' => $sell->post_feedback,
                            'created_at' => strtotime(date('Y-m-d H:i:s')),
                            'view' => $sell->view,
                            'status' => 'ongoing_pending_payment',
                        ];
                        $create_new = Sell::create($data);
                    }
                }
            }
        }
    }


    public function fixSchedules(){
    
        $schedules=Schedule::get();
        
        foreach($schedules as $schedule){
    
            if($schedule->timeval == '0'){
                $start_time=substr($schedule->start,0,10);
                $timeval=strtotime($start_time);
                $update=Schedule::where('id',$schedule->id)->update(['timeval'=>$timeval]);
            }
        }

        $classes=ClassModel::get();
        foreach($classes as $schedule){
            if($schedule->timeval == '0'){
                $start_time=substr($schedule->start,0,10);
                $timeval=strtotime($start_time);
                $update=ClassModel::where('id',$schedule->id)->update(['timeval'=>$timeval]);
            }
        }
    }

}
