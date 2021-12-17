<?php

namespace App\Http\Controllers;
use App\Models\Messages;
use App\Models\MessagesThread;
use App\Models\Sell;
use App\User;
use Illuminate\Http\Request;
use App\Models\Content;

class MessageController extends Controller
{
    public function __construct()
    {

    }

    public function messages(Request $request)
    {
        $user = auth()->user();
        $data['courses']=Content::where('type','course')->where('mode','publish')->get();
        $data['teachers']=User::where('type','Teacher')->where('mode','active')->get();
        $students=[];
        if($user->type =='Teacher'){
            $sells=Sell::where('seller_id',$user->id)->select('buyer_id')->get();
            foreach($sells as $st){
                $students[]=$st->buyer_id;
            }
            $data['students']=array_unique($students);
        }else{
            $data['students']=[];
        }

        $data['messages'] = $messages = Messages::with('threads')->orderBy('updated_at', 'DESC')->where('user_id', $user->id)->orWhere('message_to', $user->id)->get();


        return view(getTemplate() . '.user.message.list', $data);
    }

    public function messageStore(Request $request)
    {

        $user_type=$request->input('user_type');
        if($user_type=='teacher'){
            $message_to=$request->input('teacher_id');
        }else{
            $message_to=$request->input('student_id');
        }
        $msg=$request->input('msg');

        $user = auth()->user();

        $newMessageArray = [
            'user_id' => $user->id,
            'message_to' => $message_to
        ];

        $check =Messages::where('user_id',$user->id)->where('message_to',$message_to)->get();
        if($check->count() > 0){
            $newMessage=$check[0]->id;
        }else{
            $newMessage = Messages::insertGetId($newMessageArray);
        }

        $newMsgArray = [
            'message_id' => $newMessage,
            'msg' => $msg,
            'user_id' => $user->id
        ];

        $newMsg = MessagesThread::insert($newMsgArray);


        $recipient=User::where('id',$message_to)->first();
        sendMail([
            'recipient' => [$recipient->email],
            'template' => 19,
            'subject' => 'You have a new message from '.$user->name,
            'title' => 'You have a new booking '.$user->name,
            'content' => $user->name.' : '.$request->msg,
        ]);
        ## Notification Center
        //sendNotification(0, ['[t.title]' => $request->title], get_option('notification_template_ticket_new'), 'user', $user->id);

        return redirect('/user/messages');

    }

    public function messageReplyStore(Request $request)
    {
        $user = auth()->user();
        $message = Messages::find($request->input('message_id'));
        $message->updated_at = date('Y-m-d h:i:s');
        $message->update();

        $insertArray = [
            'message_id' => $request->message_id,
            'user_id' => $user->id,
            'msg' => $request->msg
        ];
        MessagesThread::insert($insertArray);
        if($message->user_id == $user->id){
            $recipient=User::where('id',$message->message_to)->first();
        }else{
            $recipient=User::where('id',$message->user_id)->first();
        }

        sendMail([
            'recipient' => [$recipient->email],
            'template' => 19,
            'subject' => 'You have a new message from '.$user->name,
            'title' => 'You have a new booking '.$user->name,
            'content' => $user->name.' : '.$request->msg,
        ]);

        return back();
    }

    public function messageConfirm(Request $request)
    {
        $up = MessagesThread::where('message_id', $request->message_id)->update(['view' => 1]);

        return back();
    }



    public function fixCourses(){
        $contents=Content::whereIn('type',['1','2'])->get();
        foreach($contents as $course){
            $date_from=substr($course->date_from,0,10);
            $temp_date_from=explode('/',$date_from);
            $new_date_from=$this->fixLength($temp_date_from[1]).'-'.$this->fixLength($temp_date_from[0]).'-'.$temp_date_from[2];

            $date_to=substr($course->date_to,0,10);
            $temp_date_to=explode('/',$date_to);
            $new_date_to=$this->fixLength($temp_date_to[1]).'-'.$this->fixLength($temp_date_to[0]).'-'.$temp_date_to[2];

            $contents_update=Content::where('id',$course->id)->update(['date_from'=>$new_date_from,'date_to'=>$new_date_to]);
        }
    }

    public function fixLength($str){
        if(strlen($str)< 2){
            $str='0'.$str;
        }
        return $str;
    }

}
