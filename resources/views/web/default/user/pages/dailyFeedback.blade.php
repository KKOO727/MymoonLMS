@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <div class="h-20"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="light-box">
                    <div class="header violet">
                        <h4>Class Feedback</h4>
                    </div>
                    <div class="clearfix mt-75"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="/user/dailyFeedback" method="POST">
                                {{csrf_field()}}
                                <input type="hidden" name="token_id" value="{{$pending_feedback['id']}}">
                            <table class="table table-borderless text-left">
                                <tr>
                                    <th width="200">Teacher</th>
                                    <td>{{$pending_feedback['teacher']}}</td>
                                </tr>
                                <tr>
                                    <th>Class</th>
                                    <td>{{$pending_feedback['title']}}</td>
                                </tr>
                                <tr>
                                    <th>Time</th>
                                    <td>{{$pending_feedback['date']}}
                                        [{{$pending_feedback['time']}}]</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><hr></td>
                                </tr>
                                <tr>
                                    <th class="text-left">How did you like your last lesson? Please rate</th>
                                    <td class="text-left">
                                        <div class="raty"></div>
                                    </td>
                                </tr>
                                <tr id="div45">
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label class="bold">Thank you, {{$user->name}}, Write now a feedback for your teacher.</label>
                                            <textarea name="feedback45" class="form-control"></textarea>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="div13">
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label class="bold">Did you face any problems within the lesson?</label><br>
                                            <input id="any_problems_yes" type="radio" name="any_problems" value="Yes"> Yes  <input type="radio" id="any_problems_no" name="any_problems" value="No"> No
                                        </div>
                                    </td>
                                </tr>

                                <tr id="div13Positive">
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label class="bold">Please let us know why you rated the lesson with <span id="st_rate"></span> stars</label><br>
                                            <textarea name="feedback13" class="form-control"></textarea>
                                        </div>
                                    </td>
                                </tr>

                                <tr id="div13Negative">
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label class="bold">Which problems did you face?</label><br>
                                            <input type="radio" name="problem" value="teacher_absent"> Teacher did not come<br>
                                            <input type="radio" name="problem" value="teacher_end_early"> Teacher had to end the lesson at an early stage<br>
                                            <input type="radio" name="problem" value="teacher_technical_issue"> Teacher had technical issues <br>
                                            <input type="radio" name="problem" value="bad_connection"> Bad connection made the lesson difficult <br>
                                            <input type="radio" name="problem" value="extremist_content"> Extremist content of the lesson <br>
                                            <input type="radio" name="problem" value="inadequate_quality"> Inadequate quality of lesson <br>
                                            <input type="radio" name="problem" value="teacher_inappropriate_behaviour"> Inappropriate behaviour of teacher <br>
                                            <input type="radio" name="problem" value="other_student_inappropriate_behaviour"> Inappropriate/extremist behaviour of other student<br>
                                        </div>

                                        <div class="form-group">
                                            <label class="bold">Explain/comment</label><br>
                                            <textarea name="feedback13Negative" class="form-control"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="bold">Would you like to request a refund?</label><br>
                                            <input type="radio" name="refund" value="Yes"> Yes  <input type="radio" name="refund" value="No"> No
                                        </div>
                                        **Please note that if you click “yes” your teacher will not get payed at all.
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2" style="text-align:center !important"> <hr><input id="submit" type="submit" name="submit" value="Submit" class="btn btn-lg btn-primary"></td>
                                </tr>
                            </table>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <style>
        #div45,#div13,#div13Positive,#div13Negative,#submit{
            display:none;
        }
    </style>
    <script>

        $('.raty').raty({
            starType: 'i',
            score: function (){
                return $(this).attr('score');
            },
            hints: [['bad 1', 'Bad'], ['poor 2', 'Poor'], ['regular 3', 'Regular'], ['good 4', 'Good'], ['gorgeous 5', 'Best']],
            click: function(score, evt) {

                if(score > 3){
                    var divs=['#div13','#div13Positive','#div13Negative'];
                    $('#div45').css('display','table-row');
                    $('#submit').css('display','block');
                    divs.forEach(element =>
                        $(element).css('display','none')
                    );

                }else{
                    var divs=['#div45','#div13Positive','#div13Negative','#submit'];
                    $('#div13').css('display','table-row');
                    $('input[name=any_problems]').attr('checked',false);
                    $('input[name=refund]').attr('checked',false);
                    divs.forEach(element => $(element).css('display','none'));
                }
            }
        });

        document.getElementById("any_problems_no").onclick = function() {
            $('#div13Positive').css('display','table-row');
            $('#div13Negative').css('display','none');
            $('#submit').css('display','block');
        };

        document.getElementById("any_problems_yes").onclick = function() {
            $('#div13Negative').css('display','table-row');
            $('#div13Positive').css('display','none');
            $('#submit').css('display','block');
        };

    </script>

@endsection
