<?php

namespace App\Http\Controllers;
use App\Models\Content;
use App\Models\ContentPart;
use App\Models\Country;
use App\Models\CourseFeedbackModel;
use App\Models\Usermeta;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class PaypalController extends Controller
{
    public function __construct()
    {

    }

    function dataMigration()
    {

        header('Content-Type: text/html; charset=utf-8');
        $filename = storage_path('app\teacher_reviews.csv');

        $delimiter = ',';

        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {


            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {


                //$group_course=Content::where('type','2')->where('tag',$row[0])->first();
                $student=Usermeta::where('option','meta_student_id')->where('value',$row[2])->first();
                $teacher=Usermeta::where('option','teacher_id')->where('value',$row[1])->first();

                if($row[4] == 0){
                    $row[4]=5;
                }



                $user_meta_data=[
                    'teacher_id'=>$teacher->user_id,
                    'student_id'=>$student->user_id,
                    'score'=>$row[4],
                    'feedback'=>$row[3],
                ];

                $add=CourseFeedbackModel::create($user_meta_data);


            }
        }
    }
    /*
    function dataMigration()
    {

        header('Content-Type: text/html; charset=utf-8');
        $filename = storage_path('app\group_courses.csv');

        $delimiter = ',';

        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {


            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {


                $status='draft';
                if($row[9] == 'TRUE'){
                    $status='publish';
                }

                $uu=Usermeta::where('option','teacher_id')->where('value',$row[10])->first();
                $user_id=$uu->user_id;

                $user_meta_data=[
                    'tag'=>$row[0],
                    'title'=>$row[1],
                    'language'=>$row[2],
                    'price'=>$row[3],
                    'max_student'=>$row[4],
                    'date_from'=>$row[5],
                    'date_to'=>$row[6],
                    'image'=>$row[7],
                    'content'=>$row[8],
                    'mode'=>$status,
                    'user_id'=>$user_id,
                    'level'=>$row[11],
                    'age_from'=>$row[12],
                    'age_to'=>$row[13],
                    'type'=>'2',
                ];
                $check_user=User::where('id',$user_id)->get()->count();
                if($check_user == 1){
                    $content=Content::create($user_meta_data);
                }


            }
        }
    }
    */

/*
    function dataMigration()
    {

        header('Content-Type: text/html; charset=utf-8');
        $filename = storage_path('app\video_course_lessons.csv');

        $delimiter = ',';

        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {


            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $uu=Content::where('tag',$row[1])->first();
                $content_id=$uu->id;

                $user_meta_data=[
                    'title'=>$row[2],
                    'url'=>$row[3],
                    'materials_file'=>$row[4],
                    'mode'=>'publish',
                    'content_id'=>$content_id,
                ];

                $content=ContentPart::create($user_meta_data);


            }
        }
    }
*/



/*
    function dataMigration()
    {

        header('Content-Type: text/html; charset=utf-8');
        $filename = storage_path('app\video_courses.csv');

        $delimiter = ',';

        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {


            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {

                $status='draft';
                if($row[7] == 'TRUE'){
                    $status='publish';
                }

                $uu=Usermeta::where('option','teacher_id')->where('value',$row[8])->first();
                $user_id=$uu->user_id;



                $user_meta_data=[
                    'tag'=>$row[0],
                    'title'=>$row[1],
                    'language'=>$row[2],
                    'price'=>$row[3],
                    'content'=>$row[4],
                    'image'=>$row[5],
                    'materials'=>$row[6],
                    'mode'=>$status,
                    'user_id'=>$user_id,
                    'level'=>$row[9],
                    'age_from'=>$row[10],
                    'age_to'=>$row[11],
                    'type'=>'3',
                ];
                $check_user=User::where('id',$user_id)->get()->count();
                if($check_user == 1){
                    $content=Content::create($user_meta_data);
                }

            }
        }
    }
*/

/*
    function dataMigration()
    {

        header('Content-Type: text/html; charset=utf-8');
        $filename = storage_path('app\students.csv');

        $delimiter = ',';

        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            $countryArray = [];
            $countries = Country::get();
            foreach ($countries as $ct) {
                $countryArray[$ct->country_name] = $ct->country_code;
            }

            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {

                $user_meta_data=[
                    'meta_student_id'=>$row[0],
                    'meta_phone'=>$row[1],
                    'meta_gender'=>$row[2],
                    'meta_country'=>$countryArray[$row[3]],
                    'meta_living_in'=>$countryArray[$row[4]],
                    'meta_identification_number'=>$row[6],
                ];
                $check_user=User::where('id',$row[5])->get()->count();
                if($check_user == 1){
                    $update_user_meta=Usermeta::updateOrNew($row[5],$user_meta_data);
                }

            }
        }
    }
           */
    /*
                $user_meta_data=[
                    'meta_dob'=>$row[1],
                    'meta_short_title'=>$row[2],
                    'meta_phone'=>$row[3],
                    'meta_gender'=>$row[4],
                    'meta_country'=>$countryArray[$row[5]],
                    'meta_living_in'=>$countryArray[$row[6]],
                    'meta_passport_file'=>$row[7],
                    'meta_certificates_file'=>$row[8],
                    'meta_resume_file'=>$row[9],
                    'meta_short_biography'=>$row[10],
                    'meta_speaking'=>$row[11],
                    'meta_extra_verified'=>$row[15],
                    'meta_public'=>$row[16],
                    'meta_verified'=>$row[18],
                    'meta_hourly_rate'=>$row[19],
                    'meta_extra_verified_request'=>$row[20],
                    'meta_paypal'=>$row[21],
                    'meta_identification_number'=>$row[23],
                    'meta_intro_video'=>$row[24],
                    'meta_age_from'=>$row[25],
                    'meta_age_to'=>$row[26],
                    'meta_teaching_experience'=>$row[27],
                ];



                $update_user_meta=Usermeta::updateOrNew($row[17],$user_meta_data);



        $avatars=Usermeta::where('option','meta_avatar')->get();
        foreach($avatars as $av){
            if(stripos($av->value,'/assets/default/images/avatar/') === false){
                $new_value='/assets/default/images/avatar/'.$av->value;

                $original_link='https://api.mymoononline.com/storage/app/public/files/'.$av->value;

                $new_loc='public/'.$av->value;

                if(!Storage::disk('local')->put($new_loc, file_get_contents($original_link))) {
                    return false;
                }

                $update_ava=Usermeta::updateOrNew($av->user_id,['meta_avatar'=>$new_value]);
            }

        }


        header('Content-Type: text/html; charset=utf-8');
        $filename=storage_path('app\teachers.csv');

        $delimiter=',';

        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            $countryArray=[];
            $countries=Country::get();
            foreach($countries as $ct){
                $countryArray[$ct->country_name]=$ct->country_code;
            }

            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                /*
                $user_meta_data=[
                    'meta_dob'=>$row[1],
                    'meta_short_title'=>$row[2],
                    'meta_phone'=>$row[3],
                    'meta_gender'=>$row[4],
                    'meta_country'=>$countryArray[$row[5]],
                    'meta_living_in'=>$countryArray[$row[6]],
                    'meta_passport_file'=>$row[7],
                    'meta_certificates_file'=>$row[8],
                    'meta_resume_file'=>$row[9],
                    'meta_short_biography'=>$row[10],
                    'meta_speaking'=>$row[11],
                    'meta_extra_verified'=>$row[15],
                    'meta_public'=>$row[16],
                    'meta_verified'=>$row[18],
                    'meta_hourly_rate'=>$row[19],
                    'meta_extra_verified_request'=>$row[20],
                    'meta_paypal'=>$row[21],
                    'meta_identification_number'=>$row[23],
                    'meta_intro_video'=>$row[24],
                    'meta_age_from'=>$row[25],
                    'meta_age_to'=>$row[26],
                    'meta_teaching_experience'=>$row[27],
                ];



                    $update_user_meta=Usermeta::updateOrNew($row[17],$user_meta_data);



            }
            fclose($handle);
        }


    }


    function dataMigration(){
        $filename=storage_path('app\users.csv');

        $delimiter=',';

        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                //dd($row);
                $user_table_data=[
                    'id'=>$row[0],
                    'username'=>$row[0],
                    'name'=>$row[1].' '.$row[2],
                    'email'=>$row[3],
                    'email_verified_at'=>$row[4],
                    'password'=>$row[5],
                    'type'=>$row[9],
                    'mode'=>'active',
                ];

                if($row[2] !='Come In'){
                    $create_user=User::create($user_table_data);

                    $update_user_meta=Usermeta::updateOrNew($row[0],['meta_avatar'=>$row['10']]);
                }


            }
            fclose($handle);
        }

    }
    */
}
