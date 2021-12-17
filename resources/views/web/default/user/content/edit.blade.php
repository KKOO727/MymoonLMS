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
                    <input type="hidden" value="1" id="current_step">
                    <input type="hidden" value="{{ $item->id }}" id="edit_id">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{url('/user/content/edit/store/')}}" id="step-1-form" class="form-horizontal">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <div class="form-group">
                                    <label class="control-label col-md-2 "
                                           for="inputDefault">{{ trans('Course Type') }}</label>
                                    <div class="col-md-10 ">
                                        <select name="type" class="form-control font-s" id="course_type"
                                                onchange="changeCourseType()" disabled required>
                                            <option value="1" @if(isset($item) && $item->type == '1') selected @endif>
                                                One to One
                                            </option>
                                            <option value="2" @if(isset($item) && $item->type == '2') selected @endif>
                                                Group Course
                                            </option>
                                            <option value="3" @if(isset($item) && $item->type == '3') selected @endif>
                                                Video Course
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2 "
                                           for="inputDefault">{{ trans('main.course_title') }}</label>
                                    <div class="col-md-10 ">
                                        <input type="text" id="courseTitle" name="title" placeholder="Course Title..."
                                               class="form-control" value="{{ $item->title }}" required>
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
                                    <label class="control-label col-md-2 " for="inputDefault">
                                        @if(isset($item) && $item->type == '1')
                                            Speaking
                                        @else
                                            Language
                                        @endif
                                    </label>
                                    <div class="col-md-10 ">
                                        <?php
                                        $speaking = array('English', 'German', 'Arabic', 'Spanish', 'French', 'Urdu/Hindi');
                                        if (isset($item->language)) {
                                            $item->language = explode(',', $item->language);
                                        } else {
                                            $item->language = array();
                                        }

                                        ?>
                                        <select name="language[]" class="form-control font-s select2" multiple required>
                                            @foreach($speaking as $spk)
                                                <option
                                                    value="{{$spk}}" @if(in_array($spk,$item->language)){{'selected'}}@endif>{{$spk}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if(isset($item) && $item->type == '2')
                                    <div class="form-group">
                                        <label class="control-label col-md-2 " for="inputDefault">
                                            Level
                                        </label>
                                        <div class="col-md-10 ">
                                            <?php
                                            $level = array('Beginner', 'Intermediate', 'Advanced');
                                            if (!isset($meta['level'])) {
                                                $meta['level'] = '';
                                            }
                                            ?>
                                            <select name="level" class="form-control font-s select2" required>
                                                @foreach($level as $lvl)
                                                    <option
                                                        value="{{$lvl}}" @if($meta['level'] == $lvl){{'selected'}}@endif>{{$lvl}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-2 ">
                                            {{ trans('Max. number of students ') }}
                                        </label>
                                        <div class="col-md-10 ">
                                            <?php
                                            if (!isset($meta['max_student'])) {
                                                $meta['max_student'] = '';
                                            }
                                            ?>
                                            <select name="max_student" class="form-control font-s select2" required>
                                                @for($i=1;$i<26;$i++)
                                                    <option
                                                        value="{{$i}}" @if($item->max_student== $i){{'selected'}}@endif>{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                @endif
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
                                    <label class="control-label col-md-2 ">
                                        @if(isset($item) && $item->type == '1')
                                            {{ trans('Price per hour') }}

                                        @else
                                            {{ trans('Price') }}
                                        @endif
                                        ({{$currency}}) {{currencySign($currency)}}
                                    </label>
                                    <div class="col-md-10 ">
                                        <div class="input-group">
                                            @php
                                                if(!isset($item->price)){
                                                     $item->price=0;
                                                     }
                                            @endphp
                                            <input type="text" name="price" value="{{ currency($item->price)}}"
                                                   class="form-control text-center" required>
                                            <span
                                                class="input-group-addon click-for-upload img-icon-s">{{ currencySign(getCurrency()) }}</span>
                                        </div>

                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label  col-md-2">Age</label>
                                    <div class="col-md-3 ">
                                        <div class="input-group">
                                            <input type="text" name="age_from" class="form-control text-center"
                                                   value="{{@$item->age_from}}" required>
                                            <span class="input-group-addon img-icon-s">From</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 ">
                                        <div class="input-group">
                                            <input type="text" name="age_to" value="{{@$item->age_to}}"
                                                   class="form-control text-center" required>
                                            <span class="input-group-addon img-icon-s">To</span>
                                        </div>
                                    </div>
                                </div>

                                @if(isset($item) && in_array($item->type, array('1','2')))
                                    <div class="form-group">
                                        <label class="control-label  col-md-2">Date</label>
                                        <div class="col-md-3 ">
                                            <div class="input-group">
                                                <input type="text" name="date_from" id="date_from"
                                                       class="form-control"
                                                       required="" value="{{@$item->date_from}}" required>
                                                <span class="input-group-addon img-icon-s">From</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 ">
                                            <div class="input-group">
                                                <input type="text" name="date_to" id="date_to"
                                                       class="form-control"
                                                       value="{{@$item->date_to}}"
                                                       required>
                                                <span class="input-group-addon img-icon-s">To</span>
                                            </div>
                                        </div>
                                    </div>

                                @endif
                                <div class="form-group">
                                    <label class="control-label  col-md-2">Course Cover</label>
                                    <div class="col-md-9 ">
                                        <div class="input-group" style="display: flex">
                                            <button id="lfm_avatar" data-input="avatar" data-preview="holder" class="btn btn-dark">Choose
                                            </button>
                                            <input id="avatar" class="form-control" dir="ltr" type="text" name="image"
                                                   value="{{ !empty($item->image) ? $item->image : '' }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label class="control-label  col-md-2">Course Status:</label>
                                    <div class="col-md-10 ">
                                        <div class="input-group">
                                            <input type="text" class="form-control text-center" value="{{strtoupper($item->mode)}}" disabled>
                                        </div>
                                        @if($item->mode !== 'publish')
                                            <br>
                                            **Please request for publish/re-publishing the course when course update is done
                                        @endif
                                    </div>

                                </div>

                                <div class="h-15"></div>
                                <hr>
                                <div class="col-md-12">
                                    <input type="submit" class="btn btn-primary btn-lg pull-right marl-s-10" value="Save draft">

                                </div>
                                <div class="h-15"></div>
                                @if($item->type == '2')
                                    ** Please click on the save button to save the above data, then enter your schedule.
                                @endif
                                <div class="h-15"></div>
                                @if(isset($item) && in_array($item->type, array('1','2')))
                                    <hr>
                                    <div id="calendar"></div>

                                    <hr>
                                    <div class="col-md-12 text-center">
                                        <a href="#" class="btn btn-sm btn-default" id="clear-btn">Remove all free
                                            schedule from calendar</a>
                                    </div>
                                    <hr>
                                @endif


                            </form>


                            @if(isset($item) && $item->type == '3')
                                <div class="link list-part-click"><h2>All Videos</h2><i
                                        class="mdi mdi-chevron-down"></i></div>

                                <div class="table-responsive">
                                    <table class="table ucp-table">
                                        <thead class="thead-s">
                                        <th class="cell-ta">{{ trans('main.title') }}</th>
                                        <th class="text-center">{{ trans('main.upload_date') }}</th>
                                        <th class="text-center">{{ trans('main.controls') }}</th>
                                        </thead>
                                        <tbody id="part-video-table-body"></tbody>
                                    </table>
                                </div>

                                <div class="accordion-off">
                                    <ul id="accordion" class="accordion off-filters-li">
                                        <li class="new-part-section open">
                                            <div class="link new-part-click"><h2>New Video</h2><i class="mdi mdi-chevron-down"></i></div>
                                            <div class="submenu dblock">
                                                <div class="h-15"></div>
                                                <form action="#" id="partsForm" method="post" class="form-horizontal">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="content_id" value="{{ $item->id }}">
                                                    <input type="hidden" name="server" value="vimeo">
                                                    <div class="form-group">
                                                        <label class="control-label  col-md-2" for="inputDefault">{{ trans('main.title') }}</label>
                                                        <div class="col-md-7 ">
                                                            <input type="text" name="title" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-3 ">
                                                            <input type="checkbox" name="preview" value="1"> Preview/Free Video
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-2 ">Video File</label>
                                                        <div class="col-md-7 ">
                                                            <div class="input-group asdf" style="display: flex;">
                                                                <input type="text" id="upload_video" name="upload_video" dir="ltr" class="form-control" style="height: 44px;">
                                                                <button type="button" id="lfm_upload_video" data-input="upload_video" data-preview="holder" class="btn btn-primary" style="padding: 0px 20px;margin: 0px 2px;">
                                                                    <span class="formicon mdi mdi-arrow-up-thick" style="width: 44px;height: 44px;"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <label class="control-label  col-md-1">{{ trans('main.sort') }}</label>
                                                        <div class="col-md-2 ">
                                                            <input type="number" name="sort" class="spinner-input form-control" required maxlength="3" min="0" max="100">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label  col-md-2" for="inputDefault">{{ trans('main.description') }}</label>
                                                        <div class="col-md-10 ">
                                                            <textarea class="form-control" rows="4" name="description"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="text-center">
                                                        <button class="btn btn-custom" id="new-part" type="submit">{{ trans('Save Video') }}</button>
                                                    </div>
                                                </form>
                                                <div class="h-15"></div>
                                            </div>
                                        </li>
                                        <li class="open edit-part-section dnone">
                                            <div class="link edit-part-click"><h2>Edit Video</h2><i class="mdi mdi-chevron-down"></i></div>
                                            <div class="submenu dblock">
                                                <div class="h-15"></div>
                                                <input type="hidden" id="part-edit-id">
                                                <form action="#" id="partsFormEdit" method="post" class="form-horizontal">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="content_id" value="{{ $item->id }}">
                                                    <input type="hidden" name="server" value="{{ $item->server }}">

                                                    <div class="form-group">
                                                        <label class="control-label  col-md-2" for="inputDefault">{{ trans('main.title') }}</label>
                                                        <div class="col-md-7 ">
                                                            <input type="text" name="title" class="form-control" required>
                                                        </div>
                                                        <div class="col-md-3 ">
                                                            <input type="checkbox" name="preview" value="1" @if($item->preview == 1){{'checked'}}@endif>
                                                            Preview/Free Video
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-md-2 ">Video File</label>
                                                        <div class="col-md-7 ">
                                                            <div class="input-group asdf" style="display: flex;">
                                                                <input type="text" id="upload_video2" name="upload_video" dir="ltr" class="form-control" style="height: 44px;">
                                                                <button type="button" id="lfm_upload_video2" data-input="upload_video2" data-preview="holder" class="btn btn-primary" style="padding: 0px 20px;margin: 0px 2px;">
                                                                    <span class="formicon mdi mdi-arrow-up-thick" style="width: 44px;height: 44px;"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <label class="control-label  col-md-1">{{ trans('main.sort') }}</label>
                                                        <div class="col-md-2 ">
                                                            <input type="number" name="sort" class="spinner-input form-control" required maxlength="3" min="0" max="100">
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="control-label col-md-2 " for="inputDefault">{{ trans('main.description') }}</label>
                                                        <div class="col-md-10  te-10">
                                                            <textarea class="form-control " rows="12" name="description" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="text-center">
                                                        <button class="btn btn-custom" id="edit-part-submit" type="submit">{{ trans('Save Video') }}</button>
                                                    </div>
                                                </form>
                                                <div class="h-15"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                            <div class="h-10"></div>

                            @if($item->mode != 'publish' & $item->mode != 'request')
                                <div class="text-center">
                                    <a href="#publish-modal" data-toggle="modal"
                                       class="btn btn-lg btn-default">{{ trans('main.publish') }}</a>
                                </div>
                            @endif
                        </div>

                        <div class="h-30"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <div class="modal fade" id="delete-part-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                    </button>
                    <h4 class="modal-title">Delete part</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure to delete this part?</p>
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

    <div class="loading" style="display:none">Loading&#8230;</div>
@endsection

@section('script')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('#lfm_upload_video,#lfm_upload_video2,#lfm_avatar').filemanager('file', {prefix: '/user/laravel-filemanager'});
    </script>
    @if(in_array($item->type,['1','2']))
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
                        start: '{{date('Y-m-d')}}',
                        <?php
                        if (isset($item->date_to) && $item->date_to != '') {
                            if (strtotime($item->date_to) > strtotime(date('Y-m-d')))
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
                        center: 'title'
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

                            //var cstart = start.startStr;
                            //var cend = start.endStr;
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
                            alert("Please fill up the Course Title,Date: From & To field to add your schedule");
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
    @endif



    <script>
        $('.editor-te').jqte({format: false});

        $('#new-part').click(function (e) {
            e.preventDefault();
            $(".loading").css('display', 'block');
            $.post('/user/content/part/store', $('#partsForm').serialize(), function (data) {
                $('#partsForm')[0].reset();
                notifyMessage('Part added successfully. Please request for publish/re-publishing when update is done.');
                setTimeout(function () {
                    location.reload();
                }, 3000);
            })
        })

        $('#edit-part-submit').click(function (e) {
            e.preventDefault();
            $(".loading").css('display', 'block');
            var id = $('#part-edit-id').val();
            $.post('/user/content/part/edit/store/' + id, $('#partsFormEdit').serialize(), function (data) {
                $('#partsFormEdit')[0].reset();
                $('.edit-part-section').hide();
                $('.new-part-section').show();
            });
            notifyMessage('Part changes saved successfully.Please request for publish/re-publishing when update is done.');
            setTimeout(function () {
                location.reload();
            }, 3000);
        })

        $('#delete-request').click(function () {
            $('#delete-part-modal').modal('hide');
            var id = $('#delete-part-id').val();
            $.get('/user/content/part/delete/' + id);
            setTimeout(function(){ refreshContent(); }, 3000);

        })


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

        $('#draft-btn').click(function () {
            var id = $('#edit_id').val();
            $.post('/user/content/edit/store/' + id, $('#step-1-form').serialize());
            notifyMessage('Your changes saved successfully');
            setTimeout(function () {
                location.reload();
            }, 3000);
        });


    </script>
    <script>
        $('document').ready(function () {
            refreshContent();
        })

        function refreshContent() {
            var id = $('#edit_id').val();
            $('#part-video-table-body').html('');
            $.get('/user/content/part/json/' + id, function (data) {
                $('#part-video-table-body').html('');
                $.each(data, function (index, item) {
                    $('#part-video-table-body').append('<tr class="text-center"><td class="cell-ta">' + item.title + '</td><td>' + item.created_at + '</td><td><span class="crticon mdi mdi-lead-pencil i-part-edit img-icon-s" pid="' + item.id + '" title="Edit"></span>&nbsp;<span class="crticon mdi mdi-delete-forever img-icon-s" data-toggle="modal" data-target="#delete-part-modal" onclick="$(\'#delete-part-id\').val($(this).attr(\'pid\'));" pid="' + item.id + '" title="Delete"></span></td></tr>');
                })
            })
        }
    </script>
    <script>

    </script>
    <script>
        $('body').on('click', 'span.i-part-edit', function () {
            var id = $(this).attr('pid');
            $.get('/user/content/part/edit/' + id, function (data) {
                $('.edit-part-section').show();
                var efrom = '#partsFormEdit ';
                $('#part-edit-id').val(id);
                $(efrom + 'input[name="upload_video"]').val(data.upload_video);
                $(efrom + 'input[name="sort"]').val(data.sort);
                $(efrom + 'input[name="title"]').val(data.title);
                $(efrom + 'input[name="server"]').val(data.server).trigger('change');
                if(data.preview == '1'){
                    $(efrom + 'input[name="preview"]').prop('checked', true);
                }else{
                    $(efrom + 'input[name="preview"]').prop('checked', false);
                }

                $(efrom + 'textarea[name="description"]').html(data.description);

            })
            if ($('.new-part-click').next('.submenu').css('display') == 'block') {
                $('.new-part-click').click();
            }
            if ($('.edit-part-click').next('.submenu').css('display') == 'none') {
                $('.new-part-click').click();
            }
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
    </script>


@endsection
