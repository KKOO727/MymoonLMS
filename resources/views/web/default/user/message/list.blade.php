@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <div class="h-20"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="light-box">
                    <div class="header">
                        <h4>Messages</h4>
                    </div>
                    <div class="clearfix mt-75"></div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist"
                                 aria-orientation="vertical">
                                @php
                                    $i=1;
                                @endphp
                                @foreach($messages as $item)
                                    @php
                                        $is_exist_unread = App\Models\MessagesThread::where('message_id', $item->id)->where('view', 0)->first();
                                    @endphp

                                    <a class="nav-link mb-3 p-3 shadow @if($is_exist_unread){{'active'}}@endif"
                                       id="v-pills-{{$item->id}}-tab" data-toggle="pill" href="#v-pills-{{$item->id}}"
                                       role="tab" aria-controls="v-pills-{{$item->id}}"
                                       aria-selected="true">{{get_username($item->user_id)}} <i
                                            class="mdi mdi-chevron-double-right"></i> {{get_username($item->message_to)}}
                                    </a>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                @php
                                    $i=1;
                                @endphp
                                @foreach($messages as $item)
                                    @php
                                        $user = auth()->user();
                                        $is_exist_unread = App\Models\MessagesThread::where('message_id', $item->id)->where('view', 0)->where('user_id', '!=', $user->id)->first();
                                    @endphp

                                    <div class="tab-pane shadow rounded bg-white p-5 @if($i==1){{'active'}}@endif"
                                         id="v-pills-{{$item->id}}" role="tabpanel"
                                         aria-labelledby="v-pills-{{$item->id}}-tab">

                                        @if($is_exist_unread)
                                        <form method="post" action="/user/messages/confirm">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="message_id" value="{{ $item->id }}" />

                                            <div class="form-group">
                                                <button type="submit" class="btn btn-custom pull-right"
                                                        value="Confirm Message">Confirm Message</button>
                                            </div>
                                            <br>
                                        </form>
                                        @endif

                                        @foreach($item->threads as $msg)
                                            @if($msg->view == 1)
                                                <p>
                                                    <strong>{{get_username($msg->user_id)}} </strong>[{{date('d-m-Y h:i', strtotime($msg->created_at))}}
                                                    ] : {{$msg->msg}}</p>
                                            @else
                                                <p style="color: #e11024;">
                                                    <strong>{{get_username($msg->user_id)}} </strong>[{{date('d-m-Y h:i', strtotime($msg->created_at))}}
                                                    ] : {{$msg->msg}}</p>
                                            @endif

                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                        <br>
                                        <form method="post" action="/user/messages/reply">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="message_id" value="{{ $item->id }}">

                                            <div class="form-group">
                                                <textarea class="form-control" placeholder="Reply..." rows="7"
                                                          name="msg" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-custom pull-right"
                                                        value="Send">{{ trans('main.send') }}</button>
                                            </div>
                                            <br>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="clearfix mt-75"></div>
                    <div class="h-20"></div>
                    <button type="button" class="btn btn-primary pull-right" data-toggle="modal"
                            data-target="#newMessageModal">
                        Start New Conversation
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="newMessageModal" class="modal fade modal-dialog-s" role="dialog">
        <div class="modal-dialog modal-dialog-s">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    New Message
                    <button type="button" class="close mart-s-10" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post" action="/user/messages/store">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="control-label" for="inputDefault">User Type</label>
                            <select name="user_type" class="form-control font-s" id="user_type"
                                    onchange="changeUserType()">
                                <option value="">Please Select</option>
                                <option value="teacher">Course Teacher</option>
                                <option value="student">Student</option>
                            </select>
                        </div>

                        <div class="form-group" id="teacher_block">
                            <label class="control-label" for="inputDefault">Teacher</label>
                            <select name="teacher_id" class="form-control font-s select2" id="teacher_id">
                                <option value="">Please Select</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="student_block">
                            <label class="control-label" for="inputDefault">Student</label>
                            <select name="student_id" class="form-control font-s select2" id="student_id">
                                @foreach($students as $student)
                                    <option value="{{$student}}">{{get_username($student)}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            <label class="control-label">{{ trans('main.description') }}</label>
                            <textarea class="form-control" rows="7" name="msg" required></textarea>
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-custom pull-left" value="Send">
                        </div>
                        <div class="h-20"></div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')



    <style>
        #teacher_block, #student_block {
            display: none
        }

        #ticket-table th, #ticket-table td {
            border: none !important;
            color: #444
        }

        .nav-pills-custom .nav-link {
            color: #aaa;
            background: #fff;
            position: relative;
        }

        .nav-pills-custom .nav-link.active {
            color: #e11024;
            background: #fff;
            font-weight: bolder;
            font-size: larger;
        }


        /* Add indicator arrow for the active tab */
        @media (min-width: 992px) {
            .nav-pills-custom .nav-link::before {
                content: '';
                display: block;
                border-top: 8px solid transparent;
                border-left: 10px solid #fff;
                border-bottom: 8px solid transparent;
                position: absolute;
                top: 50%;
                right: -10px;
                transform: translateY(-50%);
                opacity: 0;
            }
        }

        .nav-pills-custom .nav-link.active::before {
            opacity: 1;
        }

        .p-3 {
            padding: 1rem !important;
        }

        .mb-3, .my-3 {
            margin-bottom: 1rem !important;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .p-5 {
            padding: 3rem !important;
        }

        .nav-link {
            display: block;
            font-size: 14px;
        }

        .nav-link i {
            font-size: 14px
        }

        .bg-white {
            background-color: #fff !important;
        }

        .mt-75 {
            margin-top: 20px;
        }
    </style>
    <script>
        $(document).ready(function () {
            @if(isset($_GET['type']) && $_GET['type']=='contact')
            $("#user_type").val("teacher");
            changeUserType();
            document.getElementById('teacher_block').style.display = "block";

            setTimeout(function(){ $("#teacher_id").select2("val", "{{$_GET['id']}}");
                }, 1000);

            $("#newMessageModal").modal('show');
            @endif


            $("#student_id").select2({
                //dropdownParent: $("#newMessageModal")
            });
        });

        function changeUserType() {
            var type = document.getElementById('user_type').value;
            if (type === '') {
                document.getElementById('teacher_block').style.display = "none";
                document.getElementById('student_block').style.display = "none";
            } else if (type === 'teacher') {
                document.getElementById('teacher_block').style.display = "block";
                document.getElementById('teacher_id').setAttribute("required", "");
                document.getElementById('student_block').style.display = "none";
            } else {
                document.getElementById('student_id').setAttribute("required", "");
                document.getElementById('teacher_block').style.display = "none";
                document.getElementById('student_block').style.display = "block";
            }
        }

        function updateTeacher() {
            var course = document.getElementById('course_id').value;
            console.log(course);
            if (course !== '') {
                var e = document.getElementById("teacher_id");
                e.value = course;

            }
        }

    </script>
@endsection
