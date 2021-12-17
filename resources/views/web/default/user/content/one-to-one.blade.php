@extends(getTemplate() . '.user.layout.layout')
@section('pages')

    <div class="h-20"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="leftMenu">
                    <ul>
                        <li><a href="{{URL::to('/user/content?tab=courses')}}"><i class="fas fa-book"></i> My
                                Courses</a></li>
                        <li><a href="{{URL::to('/user/content/edit/onetoone')}}"><i class="fas fa-user-friends"></i> One
                                to One Configuration</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#newCourseModal"><i
                                    class="fas fa-book-open"></i> New Course</a></li>

                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="light-box">
                    <h3 class="paz no-margin violet"><i
                            class="fas fa-user-friends"></i> {{ trans('One to One Configuration') }}</h3>
                    <hr>
                    <?php
                    $speaking = array('English', 'German', 'Arabic', 'Spanish', 'French', 'Urdu/Hindi');
                    $days = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                    $start = strtotime('00:00');
                    $end = strtotime('24:00');

                    ?>

                    <div class="h-20"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="h-20"></div>
                            <form method="post" id="save-one-form" class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" value="1" id="current_step">
                                <input type="hidden" value="{{ $item->id }}" id="edit_id">

                                <div class="form-group">
                                    <label class="control-label col-md-2 "
                                           for="inputDefault">{{ trans('Course Title') }}</label>
                                    <div class="col-md-10 ">
                                        <input type="text" id="courseTitle" name="title" class="form-control"
                                               value="{{ trans('One to One') }}" readonly required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2 "
                                           for="inputDefault">Language</label>
                                    <div class="col-md-10 ">
                                        <?php

                                        if (isset($item->language)) {
                                            $item->language = explode(',', $item->language);
                                        } else {
                                            $item->language = array();
                                        }
                                        ?>
                                        <select name="language[]" class="form-control font-s select2" multiple>
                                            @foreach($speaking as $spk)
                                                <option
                                                    value="{{$spk}}" @if(in_array($spk,$item->language)){{'selected'}}@endif>{{$spk}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label " for="inputDefault">{{ trans('main.category') }}</label>
                                    <div class="col-md-10 ">
                                        <?php
                                        if (isset($item->category_id)) {
                                            $item->category_id = explode(',', $item->category_id);
                                        } else {
                                            $item->category_id = array();
                                        }

                                        ?>
                                        <select name="category_id[]" id="category_id"
                                                class="form-control font-s select2" multiple required>
                                            <option value="0">{{ trans('main.select_category') }}</option>
                                            @foreach($menus as $menu)
                                                <?php
                                                $class = '';
                                                ?>

                                                @if($menu->parent_id == 0)
                                                    <optgroup label="{{ $menu->title }}">
                                                        @if(count($menu->childs) > 0)
                                                            @foreach($menu->childs as $sub)
                                                                <?php
                                                                if ($user->level == '1' && $menu->id == '2') {
                                                                    $class = 'disabled';
                                                                }
                                                                ?>
                                                                <option value="{{ $sub->id }}"
                                                                        @if(in_array($sub->id,$item->category_id)) selected @endif {{$class}}>{{ $sub->title }}</option>
                                                            @endforeach
                                                        @else
                                                            <option value="{{ $menu->id }}"
                                                                    @if(in_array($menu->id,$item->category_id)) selected @endif {{$class}}>{{ $menu->title }}</option>
                                                        @endif
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        </select>

                                        @if($user->level == '1')
                                            <div class="h-10"></div>
                                            <a href="{{url('user/profileUpgrade')}}" class="pull-right violet bold"><i
                                                    class="fas fa-plus"></i> Enable all categories</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label  col-md-2">{{ trans('Hourly rate') }}({{$currency}})</label>
                                    <div class="col-md-10">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">

                                                    <input type="text" name="price" value="{{currency($item->price?$item->price:0)}}" class="form-control text-center numtostr">
                                                    <span class="input-group-addon click-for-upload img-icon-s">Standard</span>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <input type="text" name="trial_price" value="{{currency($item->trial_price?$item->trial_price:0)}}" class="form-control text-center numtostr">
                                                    <span class="input-group-addon click-for-upload img-icon-s">Trial</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label  col-md-2">Age</label>
                                    <div class="col-md-5 ">
                                        <div class="input-group">
                                            <input type="text" name="age_from" class="form-control text-center"
                                                   value="{{$item->age_from}}" required>
                                            <span class="input-group-addon img-icon-s">From</span>
                                        </div>
                                    </div>
                                    <div class="col-md-5 ">
                                        <div class="input-group">
                                            <input type="text" name="age_to" value="{{$item->age_to}}"
                                                   class="form-control text-center" required>
                                            <span class="input-group-addon img-icon-s">To</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label  col-md-2">Date</label>
                                    <div class="col-md-5 ">
                                        <div class="input-group">
                                            <input type="text" name="date_from" id="date_from" class="form-control"
                                                   required="" value="{{date('d-m-Y',strtotime($item->date_from))}}">
                                            <span class="input-group-addon img-icon-s">From</span>
                                        </div>
                                    </div>
                                    <div class="col-md-5 ">
                                        <div class="input-group">
                                            <input type="text" name="date_to" id="date_to" class="form-control"
                                                   value="{{$item->date_to}}" required="">
                                            <span class="input-group-addon img-icon-s">To</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 "
                                           for="inputDefault">{{ trans('main.description') }}</label>
                                    <div class="col-md-10 ">
                            <textarea class="form-control editor-te editor-te-h" placeholder="You can import HTML..."
                                      name="content" required>
                                {!! $item->content !!}
                            </textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label  col-md-2">Course Status:</label>
                                    <div class="col-md-10 ">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center"
                                                   value="{{strtoupper($item->mode)}}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="h-20"></div>
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary btn-lg"
                                            id="save-one-btn">Save
                                    </button>
                                </div>
                                <div class="h-20"></div>
                                ** Please click on the save button to save the above data, then enter your schedule.
                                <div class="h-15"></div>

                                @if(isset($item) && in_array($item->type, array('1','2')))



                                    <hr>
                                    <div id="calendar"></div>
                                    <hr>
                                    <div class="col-md-12 text-center">
                                        <a href="#" class="btn btn-sm btn-default" id="clear-btn">Remove all free
                                            schedule from calendar</a>
                                    </div>
                                @endif
                                <hr>
                            </form>

                            <h3>1:1 Monthly Plans</h3>
                            <div class="table-responsive">
                                <table class="table ucp-table">
                                    <thead class="thead-s">
                                    <th class="text-center" width="50"></th>
                                    <th class="cell-ta">{{ trans('main.title') }}</th>
                                    <th class="text-center" width="100">{{ trans('Price') }}</th>
                                    <th class="text-center" width="50">{{ trans('Lessons') }}</th>
                                    <th class="text-center" width="100">{{ trans('main.controls') }}</th>
                                    </thead>
                                    <tbody id="part-video-table-body"></tbody>
                                </table>
                            </div>
                            <div class="accordion-off">
                                <ul id="accordion" class="accordion off-filters-li">

                                    <li class="open">
                                        <div class="link new-part-click"><h2>New Plan</h2><i
                                                class="mdi mdi-chevron-down"></i></div>
                                        <div class="submenu dblock">
                                            <div class="h-15"></div>
                                            <form id="save-one-new-plan" class="form-horizontal" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="content_id" value="{{ $item->id }}">

                                                <div class="form-group">
                                                    <label class="control-label  col-md-2"
                                                           for="inputDefault">{{ trans('Plan Title') }}</label>
                                                    <div class="col-md-10 ">
                                                        <input type="text" name="plan_title" class="form-control"
                                                               required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label  col-md-2"
                                                           for="inputDefault">{{ trans('How many lessons per week') }}</label>
                                                    <div class="col-md-10  classNumber">
                                                        <input type="text" name="class_number" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label
                                                        class="control-label col-md-2 ">{{ trans('Plan price') }}({{$currency}}) {{currencySign($currency)}}</label>
                                                    <div class="col-md-8 ">
                                                        <div class="input-group" style="margin-bottom:10px">
                                                            <input type="number" min="0" name="price"
                                                                   class="form-control text-center" required>
                                                            <span
                                                                class="input-group-addon img-icon-s">{{ trans('/Month') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-center"><button type="button" class="btn btn-success btn-md"
                                                                                          id="save-one-plan-btn" style="height:40px">{{ trans('Save Plan') }}</button></div>
                                                </div>

                                            </form>
                                            <div class="h-15"></div>
                                        </div>
                                        <div class="h-15"></div>
                                    </li>
                                    <li class="open edit-part-section dnone">
                                        <div class="link edit-part-click"><h2>Edit Plan</h2><i
                                                class="mdi mdi-chevron-down"></i></div>
                                        <div class="submenu dblock">
                                            <div class="h-15"></div>

                                            <form method="post" id="save-one-edit-plan" class="form-horizontal">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="part-edit-id" id="part-edit-id">
                                                <input type="hidden" name="content_id" value="{{ $item->id }}">

                                                <div class="form-group">
                                                    <label class="control-label  col-md-2"
                                                           for="inputDefault">{{ trans('Plan Title') }}</label>
                                                    <div class="col-md-10 ">
                                                        <input type="text" name="plan_title" class="form-control"
                                                               required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label  col-md-2"
                                                           for="inputDefault">{{ trans('How many classes per week') }}</label>
                                                    <div class="col-md-10  classNumber">
                                                        <input type="text" name="class_number" class="form-control"
                                                               value="">

                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label
                                                        class="control-label col-md-2 ">{{ trans('Plan price') }}({{$currency}}) {{currencySign($currency)}}</label>
                                                    <div class="col-md-8 ">
                                                        <div class="input-group" style="margin-bottom:10px">
                                                            <input type="number" min="0" name="price"
                                                                   class="form-control text-center">
                                                            <span
                                                                class="input-group-addon img-icon-s">{{ trans('/Month') }}</span>
                                                        </div>
                                                    </div>

                                                <div class="col-md-2 text-center">
                                                    <button type="button" class="btn btn-success btn-md" style="height:40px" id="save-edit-plan-btn">{{ trans('Save Plan') }}</button>
                                                </div>
                                                </div>
                                            </form>
                                            <div class="h-15"></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>


                        </div>

                        <div class="h-30"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="delete-part-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                    </button>
                    <h4 class="modal-title">{{ trans('Delete Plan') }}</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure to delete your plan?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom"
                            data-dismiss="modal">{{ trans('main.cancel') }}</button>
                    <input type="hidden" id="delete-part-id">
                    <button type="button" class="btn btn-custom pull-left"
                            id="delete-request">{{ trans('main.yes_sure') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" id="publish-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                    </button>
                    <h4 class="modal-title">{{ trans('main.publish') }}</h4>
                </div>
                <div class="modal-body">
                    <p>{{ trans('main.publish_alert') }} </p>
                </div>
                <div class="modal-footer">

                    <form action="{{url('/user/content/publishCourse')}}" method="POST">
                        {{csrf_field()}}
                        <input type="hidden" name="course_id" value="{{$item->id}}">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">{{ trans('main.cancel') }}</button>
                        <input type="submit"
                               class="btn btn-default btn-publish-final" value="{{ trans('main.publish') }}">
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

@endsection
@section('script')
    <script>
        $('document').ready(function () {
            refreshContent();
            $('.editor-te').jqte({format: false});
            $('#one-hover').addClass('item-box-active');
        });

        $('#clear-btn').click(function () {
            let confirmAction = confirm("Are you sure to clear all free schedule ?");
            if (confirmAction) {
                $.post('{{url('user/event/removeSchedule')}}');
                setTimeout(function () {
                    location.reload();
                }, 3000);

            } else {
                return false;
            }

        });

        $('#save-one-btn').click(function () {
            $.post('/user/content/save-one-form', $('#save-one-form').serialize());
            notifyMessage('Your one to one configuration was saved.');
            setTimeout(function () {
                location.reload();
            }, 3000);

        });
        $('#save-one-plan-btn').click(function () {
            $.post('/user/content/save-one-plan', $('#save-one-new-plan').serialize());
            notifyMessage('Your plan was successfully added.');
            $('#save-one-new-plan')[0].reset();
            $('#new-day-rows').html('');
            setTimeout(function () {
                refreshContent();
            }, 3000);

        });
        $('#save-edit-plan-btn').click(function () {
            $.post('/user/content/save-one-plan', $('#save-one-edit-plan').serialize());
            notifyMessage('Your plan was successfully updated.');
            $('#save-one-new-plan')[0].reset();
            $('#new-day-rows').html('');
            $('.edit-part-section').hide();
            $('.new-part-click').click();
            setTimeout(function () {
                refreshContent();
            }, 3000);
        });

        $('body').on('click', 'i.delete-course', function () {
            $(this).parent('li').remove();
            var id = $(this).attr('cid');
            $('#precourse').val($('#precourse').val().replace(id + ',', ''));
        });
        $('body').on('click', 'span.i-part-edit', function () {
            var id = $(this).attr('pid');
            $.get('/user/content/part/edit/' + id, function (data) {
                $('.edit-part-section').show();
                var efrom = '#save-one-edit-plan ';
                $('#part-edit-id').val(id);
                $(efrom + 'input[name="plan_title"]').val(data.title);
                $(efrom + 'input[name="class_number"]').val(data.class_number);
                $(efrom + 'input[name="price"]').val(data.price);

            })
            if ($('.new-part-click').next('.submenu').css('display') == 'block') {
                $('.new-part-click').click();
            }
            if ($('.edit-part-click').next('.submenu').css('display') == 'none') {
                $('.new-part-click').click();
            }
        });

        function newUpdateDays(num) {
            $.get('/user/content/generateDaysNew?num=' + num + '&course_id=' + {{$item->id}}, function (data) {
                $('#new-day-rows').html(data);
            });
        }

        function editUpdateDays(num) {
            var part_id = $('#part-edit-id').val();
            $.get('/user/content/generateDaysEdit?id=' + part_id + '&course_id=' + {{$item->id}} + '&num=' + num, function (data) {
                $('#edit-day-rows').html(data);
            });
        }

        function getHours(id) {
            var day = $('#day' + id).val();
            $.get('/user/content/getHours?day=' + day + '&course_id=' + {{$item->id}}, function (data) {
                $('#start_time' + id).html(data);
            });
        }

        function getEditHours(id) {
            var day = $('#edit_day' + id).val();
            $.get('/user/content/getHours?day=' + day + '&course_id=' + {{$item->id}}, function (data) {
                $('#edit_start_time' + id).html(data);

            });
        }

        function refreshContent() {
            var id = $('#edit_id').val();
            $('#part-video-table-body').html('');
            $.get('/user/content/part/json/' + id, function (data) {
                $('#part-video-table-body').html('');
                $.each(data, function (index, item) {
                    $('#part-video-table-body').append('<tr class="text-center"><td></td><td class="cell-ta">' + item.title + '</td><td> ' + item.price + '</td><td>' + item.class_number + '</td><td><span class="crticon mdi mdi-lead-pencil i-part-edit img-icon-s" pid="' + item.id + '" title="Edit"></span>&nbsp;<span class="crticon mdi mdi-delete-forever" data-toggle="modal" data-target="#delete-part-modal" onclick="$(\'#delete-part-id\').val($(this).attr(\'pid\'));" pid="' + item.id + '" title="Delete"></span></td></tr>');
                })
            })
        }

        function notifyMessage(message) {
            $.notify({
                message: message
            }, {
                type: 'danger',
                allow_dismiss: false,
                z_index: '99999999',
                placement: {
                    from: "bottom",
                    align: "right"
                },
                position: 'fixed'
            });
            $('.modal').modal('hide');
        }

        $('#delete-request').click(function () {
            $('#delete-part-modal').modal('hide');
            var id = $('#delete-part-id').val();
            $.get('/user/content/part/delete/' + id);
            setTimeout(function () {
                refreshContent();
            }, 3000);

        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                contentHeight: 400,
                allDaySlot: false,
                dayHeaderContent: (args) => {
                    return moment(args.date).format('ddd D/M')
                },
                validRange: {
                    <?php
                    if (isset($item->date_from) && $item->date_from != '') {
                        echo "start: '" . date('Y-m-d', strtotime($item->date_from)) . "',";
                    }
                    if (isset($item->date_to) && $item->date_to != '') {
                        echo "end: '" . date('Y-m-d', strtotime($item->date_to)) . "'";
                    }
                    ?>
                },
                weekNumberCalculation: 'ISO',
                displayEventTime: false,
                timeZone: 'local',
                editable: true,
                initialView: 'timeGridWeek',
                nowIndicator: true,
                headerToolbar: {
                    right: 'prev,next',
                    center: 'content',
                },

                navLinks: true, // can click day/week names to navigate views
                selectable: true,
                selectHelper: true,
                longPressDelay: 1,
                selectLongPressDelay: 1,
                events: "{{\Illuminate\Support\Facades\URL::to('/user/event/fullcalendar?course='.$item->id)}}",
                eventClick: function (info) {
                    var cid = info.event.extendedProps.course_id;
                    var actual_id = '{{$item->id}}';
                    if (cid == actual_id) {

                        var deleteMsg = confirm("Do you really want to delete?");

                        if (deleteMsg) {
                            var date_to = $("#date_to").val();
                            if (date_to) {
                                $.ajax({
                                    type: "POST",
                                    url: "{{\Illuminate\Support\Facades\URL::to('/user/event/fullcalendar/delete')}}",
                                    data: 'id=' + info.event.id,
                                    success: function (response) {
                                        displayMessage("Deleted Successfully");
                                    }
                                });
                            } else {
                                alert("Please enter the Date:To field");
                            }
                        }

                        calendar.refetchEvents();
                    }
                },
                select: function (start, end, allDay) {
                    //var title = prompt('Event Title:');
                    var title = $("#courseTitle").val();
                    var date_from = $("#date_from").val();
                    var date_to = $("#date_to").val();
                    if (title && date_from && date_to) {

                        var cstart = start.start.toISOString();
                        var cend = start.end.toISOString();

                        var course = '{{ $item->id }}';
                        var user = '{{ Auth::user()->id }}';
                        var course_type = '{{ $item->type }}';
                        $.ajax({
                            url: "{{\Illuminate\Support\Facades\URL::to('/user/event/fullcalendar/create')}}",
                            data: 'title=' + title + '&start=' + cstart + '&end=' + cend + '&course=' + course + '&user_id=' + user + '&course_type=' + course_type + '&from=' + date_from + '&to=' + date_to,
                            type: "POST",
                            success: function (data) {
                                displayMessage("Added Successfully");
                            },
                            error: function (data) {
                                //console.log(data);
                                console.log('error');
                            }
                        });

                    } else {
                        alert("Please fill up the  -Date: From & To- fields to add your schedule");
                    }
                    calendar.refetchEvents();
                },
            });

            calendar.render();

        });

        function displayMessage(message) {
            $(".response").html("<div class='success'>" + message + "</div>");
            setInterval(function () {
                $(".success").fadeOut();
            }, 1000);
        }


    </script>

@endsection
