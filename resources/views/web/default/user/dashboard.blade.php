@extends(getTemplate() . '.user.layout.layout')
@section('title')
    {{ $setting['site']['site_title'] }} -
    {{ trans('main.user_panel') }}
@endsection
@section('pages')
    <div class="h-20"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-xs-12">
                <div class="light-box">
                    <?php echo getProfileBox($user['id']);?>
                    <?php echo getProfileText($user['id'],false);?>
                </div>
                <div class="ucp-section-box">
                    <div class="header paz"></div>
                    <div class="body">
                        <h4>{{ trans('MyMoon Balance') }}</h4>
                        <h4 class="bold">
                        {{currency(getUserbalance($user['id']))}}

                        </h4>
                    </div>
                </div>


            </div>
            <div class="col-md-9 col-xs-12">
                <div class="light-box">

                    @if($user['mode'] == 'deactive')
                        <div class="alert alert-danger text-center">Your account is temporarily deactivated because you dont have any schedule.Please click the below button to activate your account.<br>

                          <br>  <a href="{{url('/user/profile?tab=settings')}}" class="btn btn-default">Activate Account</a>
                        </div>
                        @endif

                    <div class="header">
                        <h4>Upcoming Classes</h4>
                        [Current UTC Time : {{date('d.m.Y H:i:s', time())}}]
                    </div>

                    <table class="table table-bordered table-responsive-sm">
                        <thead>
                        <tr>
                            <th>Course Details</th>
                            <th>Time (UTC/GMT)</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="3"><a href="#" class="btn btn-md btn-success pull-right" onclick="startMeeting('MyMoonTestClassroom')">Join Test Classroom</a></td>
                        </tr>
                        <tr>
                            <td colspan="3">One to One Course</td>
                        </tr>

                        @if($one->count() == 0)
                            <tr>
                                <td colspan="3">No classes found</td>
                            </tr>
                            @endif
                        @foreach($one as $event)
                            <?php
                                $only_event_date = substr($event->start, 0, 10);
                                $startHour = substr($event->start, -9, -4);
                                $endHour = substr($event->end, -9, -4);

                                $start = strtotime($only_event_date . ' ' . $startHour);
                                $end = strtotime($only_event_date . ' ' . $endHour);

                                if($user['type'] == 'Teacher'){
                                    $nowtime = strtotime(date('Y-m-d H:i:s', time()))+1500;
                                    $end = $end+1500;
                                }else{
                                    $nowtime = strtotime(date('Y-m-d H:i:s', time()))+600;
                                    $end = $end+600;
                                }
                            ?>
                            <tr>
                                <td>
                                    <strong>Course :</strong> {{$event->title}}<br>
                                    <strong>Teacher :</strong> {{get_username($event->user_id)}}<br>
                                    <strong>Student :</strong> {{get_username($event->booking_user_id)}}<br>
                                    <strong>Student Age :</strong> {{get_student_age($event->booking_user_id)}}
                                </td>
                                <td>{{date('d.m.Y',strtotime($only_event_date))}}  {{$startHour}}-{{$endHour}}</td>
                                <td>
                                    <?php
                                    if ($nowtime >= $start && $nowtime < $end) {
                                        $meeting_link = "'".$event['id'] . '-' . $event['course_id'] . '-' . $event['user_id'] . '-' . $start."'";
                                        echo '<a href="#" class="btn btn-sm btn-primary" onclick="startMeeting('. $meeting_link.')">Join Class</a>';
                                    }
                                    else if($event->completed == '1'){
                                        echo 'Completed';
                                    }else {
                                        echo "Ongoing";
                                    }
                                    ?>
                                </td>
                            </tr>

                        @endforeach
                        <tr>
                            <td colspan="3" class="bg-primary">Group Course</td>
                        </tr>
                        @if($group->count() == 0)
                            <tr>
                                <td colspan="3">No classes found</td>
                            </tr>
                        @endif
                        @foreach($group as $event)
                            <?php
                            $only_event_date = substr($event->start, 0, 10);
                            $startHour = substr($event->start, -9, -4);
                            $endHour = substr($event->end, -9, -4);

                            $start = strtotime($only_event_date . ' ' . $startHour);
                            $end = strtotime($only_event_date . ' ' . $endHour);

                            if($user['type'] == 'Teacher'){
                                $nowtime = strtotime(date('Y-m-d H:i:s', time()))+600;
                            }else{
                                $nowtime = strtotime(date('Y-m-d H:i:s', time()));
                            }
                            $students=[];
                            $students=explode(',',$event->booking_user_id);
                            ?>
                            <tr>
                                <td><strong>Course :</strong> {{$event->title}}<br>
                                        <strong>Teacher :</strong> {{get_username($event->user_id)}}<br>
                                            <strong>Students :</strong>
                                    @foreach($students as $st)
                                    {{get_username($st)}},
                                    @endforeach
                                </td>
                                <td>{{date('d.m.Y',strtotime($only_event_date))}}  {{$startHour}}-{{$endHour}}</td>
                                <td>
                                    <?php
                                    if ($nowtime >= $start && $nowtime < $end) {
                                        $meeting_link = "'".$event['id'] . '-' . $event['course_id'] . '-' . $event['user_id'] . '-' . $start."'";
                                        echo '<a href="#" class="btn btn-sm btn-primary" onclick="startMeeting('. $meeting_link.')">Join Class</a>';
                                    }
                                    if($event->completed == '1'){
                                        echo 'Completed';
                                    }
                                    ?>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>


                <div class="light-box">
                    <div class="header">
                        <h4>Completed Classes</h4>
                    </div>

                    <table class="table table-bordered table-responsive-sm">
                        <thead>
                        <tr>
                            <th>Course</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($completed as $event)
                                <?php
                                $only_event_date = substr($event->start, 0, 10);
                                $startHour = substr($event->start, -9, -4);
                                $endHour = substr($event->end, -9, -4);
                                ?>
                                <tr>
                                    <td>{{$event->title}}<br>
                                        [{{get_username($event->user_id)}}]
                                    </td>
                                    <td>{{date('d.m.Y',strtotime($only_event_date))}}  {{$startHour}}-{{$endHour}}</td>
                                    <td>
                                        <?php
                                        if($event->completed == '1'){
                                            echo 'Completed';
                                        }else{
                                            echo "Past";
                                        }
                                        ?>
                                    </td>
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="h-10"></div>
    <style>
        .table tr td{text-align:left}
    </style>
@endsection

@section('script')
    <script>$('#dashboard-hover').addClass('item-box-active');</script>
@endsection
