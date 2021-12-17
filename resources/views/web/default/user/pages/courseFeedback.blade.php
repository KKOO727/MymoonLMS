@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <div class="h-20"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="light-box">
                    <div class="header violet">
                        <h4>Course Feedback</h4>
                    </div>
                    <div class="clearfix mt-75"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="/user/courseFeedback" method="POST">
                                {{csrf_field()}}
                                <input type="hidden" name="token_id" value="{{$pending_feedback['id']}}">
                                <input type="hidden" name="sell_id" value="{{$pending_feedback['sell_id']}}">
                            <table class="table table-borderless text-left">
                                <tr>
                                    <th width="200">Teacher</th>
                                    <td>{{$pending_feedback['teacher']}}</td>
                                </tr>
                                <tr>
                                    <th>Course</th>
                                    <td>{{$pending_feedback['title']}}</td>
                                </tr>

                                <tr>
                                    <td colspan="2"><hr></td>
                                </tr>
                                <tr>
                                    <th class="text-left">How did you like your course? Please rate</th>
                                    <td class="text-left">
                                        <div class="raty"></div>
                                    </td>
                                </tr>
                                <tr id="div45">
                                    <td colspan="2">
                                        <div class="form-group">
                                            <label class="bold">Thank you, {{$user->name}}, Please write now a feedback for your teacher.</label>
                                            <textarea name="feedback45" class="form-control"></textarea>
                                        </div>
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
        #div45,#submit{
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
                    var divs=['#div45','#submit'];
                    $('#div45').css('display','table-row');
                    $('#submit').css('display','block');

            }
        });


    </script>

@endsection
