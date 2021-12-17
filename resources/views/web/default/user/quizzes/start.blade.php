@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
    - {{ $quiz->name }}
@endsection

@section('style')
    <link rel="stylesheet" href="/assets/default/clock-counter/flipTimer.css"/>
@endsection
@section('page')
    <!-- MultiStep Form -->
    <div class="container-fluid" id="grad1">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2 quiz-wizard">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div>
                            <div>
                                <h2 class="quiz-name">{{ $quiz->name }}</h2>
                                <span class="course-name d-block">{{ $quiz->content->title }}</span>
                            </div>
                            <div class="quiz-info">
                                <span>Question : <small>{{ count($quiz->questions) }}</small></span>
                                <span>Pass Mark : <small>{{ $quiz->pass_mark }}</small></span>
                                <span>Total Mark : <small>{{ (count($quiz->questionsGradeSum) > 0) ? $quiz->questionsGradeSum[0]->grade_sum : 0 }}</small></span>
                            </div>
                        </div>
                        <div class="quiz-time">
                            @if (!empty($quiz->time))
                                <div class="flipTimer">
                                    @if ($quiz->time > 60)
                                        <div class="hours">
                                            <span class="time-title">{{ trans('main.hour') }}</span>
                                        </div>
                                    @endif
                                    <div class="minutes"><span class="time-title">{{ trans('main.minute') }}</span></div>
                                    <div class="seconds"><span class="time-title">{{ trans('main.second') }}</span></div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <form id="quizForm" action="/user/quizzes/{{ $quiz->id }}/store_results" method="post" class="quiz-form">
                                {{ csrf_field() }}
                                <input type="hidden" name="quiz_result_id" value="{{ $newQuizStart->id }}">
                                @foreach ($quiz->questions as $question)
                                    <fieldset>
                                        <input type="hidden" name="question[{{ $question->id }}]" value="{{ $question->id }}">
                                        <div class="form-card">
                                            <h3 class="question-title">{{ $loop->iteration }} - {{ $question->title }}</h3>
                                            @if($question->type == 'audio')
                                                <a href="{{url($question->file)}}" class="btn btn-primary">View File</a><br><br>
                                                @endif


                                            @if ($question->type == 'multiple' and count($question->questionsAnswers))
                                                <div class="answer-items">
                                                    @foreach ($question->questionsAnswers as $answer)
                                                        @if (!empty($answer->title))
                                                            <div class="form-radio">
                                                                <input id="asw{{ $answer->id }}" type="radio" name="question[{{ $question->id }}][answer]" value="{{ $answer->id }}">
                                                                <label class="answer-label" for="asw{{ $answer->id }}">
                                                                    <span class="answer-title">{{ $answer->title }}</span>
                                                                </label>
                                                            </div>
                                                        @elseif(!empty($answer->image))
                                                            <div class="form-radio">
                                                                <input id="asw{{ $answer->id }}" type="radio" name="question[{{ $question->id }}][answer]" value="{{ $answer->id }}">
                                                                <label for="asw{{ $answer->id }}">
                                                                    <div class="image-container">
                                                                        <img src="{{ $answer->image }}" class="fit-image" alt="">
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @elseif ($question->type == 'descriptive')
                                                <textarea name="question[{{ $question->id }}][answer]" rows="6" class="form-control"></textarea>
                                            @elseif ($question->type == 'audio')

                                                    <div class="row form-group">
                                                        <button id="lfm_avatar" data-input="avatar" data-preview="holder" class="btn btn-primary col-md-3" style="height:40px;border-radius:0">Upload file/audio</button>
                                                        <div class="input-group col-md-9" style="display: flex">
                                                            <input id="avatar" class="form-control" dir="ltr" type="text" name="question[{{ $question->id }}][answer]" value="">
                                                            <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="meta_avatar"></div>
                                                        </div>
                                                    </div>

                                            @endif
                                        </div>
                                        <div class="card-actions d-flex align-items-center">
                                            @if ($loop->iteration > 1)
                                                <button type="button" class="action-button previous btn btn-custom">prev Step</button>
                                            @endif
                                            @if ($loop->iteration < $loop->count)
                                                <button type="button" class="action-button next btn btn-custom">Next Step</button>
                                            @endif
                                            <button type="button" class="action-button finish btn btn-danger">finish</button>
                                        </div>
                                    </fieldset>
                                @endforeach
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="finishModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="z-index: 1050">
            <!-- Modal content-->
            <div class="modal-content modal-sm">
                <div class="modal-body" style="padding: 32px;text-align: center">
                    <p>{{ trans('main.finish_quiz_alert') }}</p>
                    <div class="d-flex align-items-center" style="margin-top: 24px ;justify-content: space-around">
                        <button id="SubmitResult" class=" btn btn-custom">
                            {{ trans('main.yes_sure') }}
                        </button>
                        <button type="button" data-dismiss="modal" class="btn btn-danger">{{ trans('main.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('#lfm_avatar,#lfm_profile_image,#lfm_melli_card').filemanager('file', {prefix: '/user/laravel-filemanager'});
    </script>
    <script type="application/javascript" src="/assets/default/clock-counter/jquery.flipTimer.js"></script>
    <script>

        $(document).ready(function () {
                @if(isset($quiz->time))
            var currentTime = new Date();
            currentTime.setMinutes(currentTime.getMinutes() + {{ $quiz->time }});


            $('.flipTimer').flipTimer({
                direction: 'down',
                date: currentTime,
                callback: function () {
                    $('body .action-button.finish').remove();
                    $('#quizForm').submit();
                },
            });
                @endif

            var current_fs, next_fs, previous_fs; //fieldsets
            var opacity;

            $(".next").click(function () {

                current_fs = $(this).parent().parent();
                next_fs = $(this).parent().parent().next();

                next_fs.show();

                current_fs.animate({opacity: 0}, {
                    step: function (now) {
                        opacity = 1 - now;
                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({'opacity': opacity});
                    },
                    duration: 600
                });

            });

            $(".previous").click(function () {

                current_fs = $(this).parent().parent();
                previous_fs = $(this).parent().parent().prev();

                previous_fs.show();


                current_fs.animate({opacity: 0}, {
                    step: function (now) {
                        opacity = 1 - now;
                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        previous_fs.css({'opacity': opacity});
                    },
                    duration: 600
                });
            });

            $('body').on('click', '.action-button.finish', function (e) {
                e.preventDefault();
                $('#finishModal').modal('show');
            });

            $('body').on('click', '#SubmitResult', function (e) {
                e.preventDefault();
                $('#quizForm').submit();
            });
        });
    </script>
@endsection
