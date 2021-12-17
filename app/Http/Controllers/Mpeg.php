<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Http\Requests;

class Mpeg extends Controller
{

    public function progressBar($txt)
    {
        $content = @file_get_contents('d:/block.txt');

        if($content){
            //get duration of source
            preg_match("/Duration: (.*?), start:/", $content, $matches);

            $rawDuration = $matches[1];

            //rawDuration is in 00:00:00.00 format. This converts it to seconds.
            $ar = array_reverse(explode(":", $rawDuration));
            $duration = floatval($ar[0]);
            if (!empty($ar[1])) $duration += intval($ar[1]) * 60;
            if (!empty($ar[2])) $duration += intval($ar[2]) * 60 * 60;

            //get the time in the file that is already encoded
            preg_match_all("/time=(.*?) bitrate/", $content, $matches);

            $rawTime = array_pop($matches);

            //this is needed if there is more than one match
            if (is_array($rawTime)){$rawTime = array_pop($rawTime);}

            //rawTime is in 00:00:00.00 format. This converts it to seconds.
            $ar = array_reverse(explode(":", $rawTime));
            $time = floatval($ar[0]);
            if (!empty($ar[1])) $time += intval($ar[1]) * 60;
            if (!empty($ar[2])) $time += intval($ar[2]) * 60 * 60;

            //calculate the progress
            $progress = round(($time/$duration) * 100);

            //echo "Duration: " . $duration . "<br>";
            //echo "Current Time: " . $time . "<br>";
            //echo "Progress: " . $progress . "%";

            return $progress;

        }
    }
    public function Convert(Request $request)
    {
        $patch = public_path().$request->upload_video;
        exec("ffmpeg -y -i $patch -c:v libx264 -preset ultrafast d:/vhhhjdeo.mp4 1> d:/block.txt 2>&1");
        return Redirect::to(URL::previous().'#convert');
    }
    public function progress(){
        $content = file_get_contents("d:/block.txt");
        echo $this->progressBar("d:/block.txt");
    }
    public function screenShot(Request $request)
    {
        $patch = public_path().$request->upload_video;
        empty($request->intval)?$intval=20:$intval = $request->intval;
        exec("ffmpeg -i $patch -deinterlace -an -ss $intval -f mjpeg -t 1 -r 1 -y -s $request->resolution d:/thumbnail-$request->id.jpg 2>&1");
        return back();
    }
}
