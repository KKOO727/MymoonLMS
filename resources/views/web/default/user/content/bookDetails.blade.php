@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{'Book Teacher'}}
@endsection
@section('page')
    <?php
    $currency=getCurrency();
    $base_url = baseURL();
    $user_balance=getUserbalance($user->id);
    ?>
    <div class="h-20"></div>
    <div class="h-20"></div>
    <div class="loading" style="display:none">Loading&#8230;</div>
    <div class="container-fluid">
        <div class="row product-body">
            <div class="container">

                <div class="col-md-8 col-xs-12 product-part-container">

                    <div class="course_details">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="teacher-row">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="image-box text-center">
                                                <?php echo getProfileBox($profile->id);?>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <?php echo getProfileText($profile->id, true);?>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <hr class="no-margin">

                    <div class="user-tabs">
                        <h4 class="violet bold"><span class="proicon mdi mdi-account-plus"></span> Course Details:</h4>
                        <strong>Course Type : </strong>
                        @if($type == 'plan') {{'One to One'}} @elseif($type == 'video') {{'Video Course'}}@else {{'Group Course'}}@endif

                        <br>

                        <strong>Course Description : </strong> {{$content->content}}<br>

                        @if($type == 'plan')
                            <h4 class="violet bold"><span class="proicon mdi mdi-buffer"></span> Plan Details: {{$content_part->title}}</h4>
                        @else
                            <h4 class="violet bold"><span class="proicon mdi mdi-buffer"></span> Course : {{$content->title}}</h4>
                        @endif

                        @if($type == 'plan')
                            <table class="table table-bordered">
                                <tr>
                                    <th>Total Classes</th>
                                    <td>{{$content_part->class_number}}</td>
                                    <th>Class Duration</th>
                                    <td>{{$duration}} Minutes</td>
                                </tr>

                            </table>
                            <h4 class="violet bold"><span class="proicon mdi mdi-calendar"></span> Class Schedule:</h4>
                            <a id="clearfix" href="#"></a>
                            <div id="calendar"></div>
                        @endif

                        @if($type == 'video')
                            <div class="product-part-container">
                                <div class="tab-content">
                                    <ul class="part-ul">
                                        @foreach($videos as $part)
                                            <li>
                                                <div class="part-links">
                                                    <div class="col-md-1 hidden-xs tab-con">
                                                        @if($part['free'] == 1)
                                                            <span class="playicon mdi mdi-play-circle"></span>
                                                        @else
                                                            <span class="playicon mdi mdi-lock"></span>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-5 col-xs-10 tab-con">
                                                        <label>{{ $part['title'] }}</label>
                                                    </div>
                                                    <div class="col-md-2 tab-con">
                                                    </div>
                                                    <div class="col-md-2 text-center hidden-xs tab-con">
                                                        <span>{{ $part['size'] }} {{ trans('main.mb') }}</span>
                                                    </div>
                                                    <div class="col-md-2 hidden-xs tab-con">
                                                        <span>{{ $part['duration'] }} {{ trans('main.minute') }}</span>
                                                    </div>
                                                    @if(1==2)
                                                        <div class="col-md-1 col-xs-2 tab-con">
                                                            <span class="download-part" data-href="/video/download/{{ $part['id'] }}"><span class="mdi mdi-arrow-down-bold"></span></span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                        @if(isset($meta['document']) and $meta['document']!='')
                                            <li class="document">
                                                <div class="col-md-1">
                                                    <span class="clip"></span>
                                                </div>
                                                <div class="col-md-10 text-left" style="text-align: left;">
                                                    <label>{{ trans('main.documents') }}</label>
                                                </div>
                                                <div class="col-md-1 text-center">
                                                    <span class="download-part" data-href="{{ $meta['document'] }}"><span class="mdi mdi-arrow-down-bold"></span></span>
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        @endif

                        @if($type == 'group')
                            <table class="table table-bordered">
                                <tr>
                                    <th>Date From</th>
                                    <td>{{$content->date_from}}</td>
                                    <th>Date To</th>
                                    <td>{{$content->date_to}}</td>
                                </tr>
                                <tr>
                                    <th>Max Student</th>
                                    <td>{{$content->max_student}}</td>

                                </tr>

                            </table>
                            <h4 class="violet bold"><span class="proicon mdi mdi-calendar"></span> Class Schedule:</h4>
                            <table class="table table-bordered table-striped violet-table">
                                <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Class Start Time</th>
                                    <th>Class End Time</th>
                                </tr>
                                </thead>
                                @foreach($schedule as $sch)
                                    <tr>
                                        <td>{{$sch['day']}}</td>
                                        <td>{{date("g:i A", $sch['start_time'])}}</td>
                                        <td>{{date("g:i A", $sch['end_time'])}}</td>
                                    </tr>

                                @endforeach
                            </table>
                        @endif

                        <hr>


                    </div>

                    <div class="h-30"></div>
                </div>
                <div class="col-md-4 col-xs-12">
                    @if($user->type=='Student')
                    @if(!$buy)
                        <div class="coupon">
                            <form class="">
                                <table class="table table-borderless">
                                    <tr>
                                        <td colspan="2"><strong>Discount Code/ Perk Discount</strong></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" id="coupon_input" name="coupon" class="form-control" value=""></td>
                                        <td><a href="#" class="btn btn-primary" onclick="applyCoupon()">Apply</a></td>
                                    </tr>
                                </table>
                            </form>
                            <hr>
                            <div class="donation classNumber">

                                <?php

                                $amounts = array('1', '3', '5', '10');
                                ?>
                                <table class="table table-borderless">
                                    <tr>
                                        <td colspan="2"><strong>Give a tip</strong></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @foreach($amounts as $amount)
                                                <div class="class_item">
                                                    <input type="radio" id="{{$amount}}x" name="donate"
                                                           onclick="donate({{$amount}},false)" value="{{$amount}}">
                                                    <label for="{{$amount}}x">{{currencySign($currency)}}{{$amount}}</label>
                                                </div>
                                            @endforeach
                                            <div class="class_item">
                                                <input type="text" name="donate" id="donate_own" class="form-control" onkeyup="donate(0,true)" placeholder="Your own amount">
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                            <hr>

                            <h4 class="violet bold">Subtotal</h4>
                            <table class="table table-bordered blue-table text-left">
                                <tr>
                                    <th style="width:100px">Amount</th>
                                    <td>{{currency($price)}}</td>
                                </tr>
                                <tr>
                                    <th>Discount</th>
                                    <td><span id="discount_div">0</span></td>
                                </tr>
                                <tr>
                                    <th>Donation</th>
                                    <td><span id="donation_div">0</span></td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td><span id="total_div">{{currency($price)}}</span></td>
                                </tr>
                            </table>
                            <hr>
                            <h4 class="violet bold">Payment Method</h4>
                            <ul class="list-none">
                                <li>
                                    <input type="radio" id="credit" name="payment_method" value="credit" required="" checked>
                                    <label for="credit">MyMoon Credit - [Available {{currency($user_balance)}}]</label>
                                </li>
                                <!--<li>
                                    <input type="radio" id="card" name="payment_method" value="card" required="" disabled>
                                    <label for="card">Credit Card</label>
                                </li>
                                <li>
                                    <input type="radio" id="paypal" name="payment_method" value="paypal" required="" disabled>
                                    <label for="paypal">Paypal</label>
                                </li>-->
                            </ul>
                            <hr>
                            <a href="#" class="btn btn-primary" style="display:block" onclick="confirmBooking()">Pay / Confirm</a>
                        </div>
                </div>
                @else
                    <h4 class="violet bold"><span class="proicon mdi mdi-calendar"></span> You have already purchased this course</h4>
                @endif
                @endif
            </div>
        </div>
    </div>


    <div class="h-30"></div>
    <input type="hidden" name="crsf_cop" id="crsf_cop" value="0">
    <input type="hidden" name="crsf_cop" id="crsf_don" value="0">

@endsection
@section('script')

    @if($type == '1to1' || $type == 'plan')
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
                        if (isset($content->date_from) && $content->date_from != ''){
                            echo "start: '".date('Y-m-d', strtotime($content->date_from))."',";
                        }
                        if (isset($content->date_to) && $content->date_to != ''){
                            echo "end: '".date('Y-m-d', strtotime($content->date_to))."'";
                        }
                        ?>
                    },
                    weekNumberCalculation: 'ISO',
                    displayEventTime: false,
                    displayEventTitle: false,
                    timeZone: 'local',
                    editable: true,
                    initialView: 'timeGridWeek',
                    nowIndicator: true,
                    headerToolbar: {
                        right: 'prev,next today',
                        center: 'title',
                        left: 'listWeek,timeGridWeek'
                    },

                    navLinks: true, // can click day/week names to navigate views
                    selectable: true,
                    selectHelper: true,
                    longPressDelay: 1000,
                    selectLongPressDelay: 1000,
                    events: "{{\Illuminate\Support\Facades\URL::to('/user/event/individualSchedule?course='.$content->id)}}",
                    select: function (start) {

                            //var cstart = start.startStr;
                            //var cend = start.endStr;
                        var cstart = start.start.toISOString();
                        var cend = start.end.toISOString();

                            var course = '{{ $content->id }}';
                            var user = '{{ $user->id }}';
                            var course_type = '{{ $content->type }}';
                            var plan = '{{ $content_part->id }}';
                            $.ajax({
                                url: "{{\Illuminate\Support\Facades\URL::to('/user/event/fullcalendar/book')}}",
                                data: 'start=' + cstart + '&end=' + cend + '&course=' + course + '&plan='+ plan,
                                type: "POST",
                                success: function (data) {

                                    if(data ==='not found'){
                                        confirm('No class found on that time slot');
                                    }else if(data ==='not available'){
                                        alert('This slot is already booked');
                                    }else if(data ==='exceeded'){
                                        alert('Your maximum slot number exceeded. Please remove any of the previous slots. ');
                                    }
                                    calendar.unselect();
                                },
                                error: function (data) {
                                    //console.log(data);
                                    console.log('error');
                                }
                            });
                        calendar.refetchEvents();

                    },
                    eventClick: function (info) {
                        var course = '{{ $content->id }}';
                        var plan = '{{$content_part->id}}';
                        var cid = info.event.extendedProps.course_id;
                        if (cid == course) {
                            var deleteMsg = confirm("Do you really want to delete?");
                            if (deleteMsg) {
                                $.ajax({
                                    type: "POST",
                                    url: "{{\Illuminate\Support\Facades\URL::to('/user/event/fullcalendar/deletePlanBooking')}}",
                                    data: 'id=' + info.event.id+ '&course_id='+'{{ $content->id }}'+'&plan_id='+plan,
                                    success: function (response) {
                                        displayMessage("Deleted Successfully");
                                    }
                                });
                            }

                            calendar.refetchEvents();
                        }
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
        $('document').ready(function () {
            $('input[name=donate]').attr('checked', false);
            $("#donate_own").val('');
        })

        function donate(amount, manual) {
            if (manual === true) {
                amount = $("#donate_own").val();
                $('input[name=donate]').attr('checked', false);
            } else {
                $("#donate_own").val('');
            }
            $.post("/user/book/updateDonationPrice", {don: amount,main:'{{$price}}',cur:'{{$currency}}'}, function (result) {

                var total = result;
                $("#total_div").html(total);
                $("#donation_div").html('{{currencySign($currency)}}'+amount);
                $("#crsf_don").val(amount);
            });

        }

        function confirmBooking() {
            $(".loading").css('display','block');
            var don = $("#crsf_don").val();
            @if($type == 'plan')
            $.post("/user/book/creditPay", {don: don, type: '{{$type}}',cur:'{{$currency}}', content_id: '{{$content->id}}', plan_id: '{{$content_part->id}}',dur:'{{$duration}}'}, function (result) {
                if (result == 'ok') {
                    window.location.replace("/user/video/buy");
                }else{
                    if(result === 'noCredit'){
                        alert('You do not have enough credit. Please top up from your balance page');
                        window.location.replace("/user/balance?tab=log");
                    }else{
                        alert(result);
                        location.reload();
                    }

                }
            });
            @else
            $.post("/user/book/creditPay", {don: don, type: '{{$type}}', content_id: '{{$content->id}}', plan_id: '0'}, function (result) {
                if (result == 'ok') {
                    window.location.replace("/user/video/buy");
                }else{
                    if(result === 'noCredit'){
                        alert('You do not have enough credit. Please top up from your balance page');
                        window.location.replace("/user/balance?tab=log");
                    }else{
                        alert(result);
                        location.reload();
                    }
                }
            });
            @endif

        }
    </script>

@endsection

