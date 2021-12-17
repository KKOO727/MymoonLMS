@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
@endsection

@section('page')
    <!-- MultiStep Form -->
    <div class="container-fluid" id="grad1">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2 quiz-wizard">
                <div class="card quiz-result">
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
                        <div class="result-mark {{ $quiz_result->status }}">
                            <strong>{{ $quiz_result->user_grade }}</strong>
                            <span>({{ $quiz_result->status == 'pass' ? trans('main.passed') : ($quiz_result->status == 'fail' ? trans('main.failed') : trans('main.waiting')) }})</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="result-card">
                                <img src="/assets/default/images/{{ $quiz_result->status == 'pass' ? 'winners.png' : 'feeling.png'}}" alt="">
                                <h3 class="result-msg">{{ $quiz_result->status == 'pass' ? trans('main.quiz_winners') : ($quiz_result->status == 'fail' ? trans('main.quiz_feeling') : trans('main.quiz_waiting')) }}</h3>
                                @if ($quiz_result->status == 'fail' and $canTryAgain)
                                    <a href="/user/quizzes/{{ $quiz->id }}/start" class="btn btn-custom btn-danger">{{ trans('main.try_again') }}</a>
                                    @elseif ($quiz_result->status == 'pass' and $quiz->certificate)
                                    <a href="/user/certificates/{{ $quiz_result->id }}/download" class="btn btn-custom btn-danger">{{ trans('main.download_certificate') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {

        });
    </script>
@endsection
