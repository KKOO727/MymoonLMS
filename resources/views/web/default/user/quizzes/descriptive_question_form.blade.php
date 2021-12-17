<form id="descriptiveQuestionForm" action="@if (!empty($question_edit)) /user/questions/{{ $question_edit->id }}/update @else /user/quizzes/{{ $quiz->id }}/questions @endif" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="type" value="descriptive">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group @error('title') has-error @enderror">
                <label class="control-label tab-con">{{ trans('main.question_title') }}</label>
                <input type="text" name="title" value="{{ !empty($question_edit) ? $question_edit->title : '' }}" placeholder="{{ trans('main.question_title') }}" class="form-control">
                <div class="help-block">@error('title') {{ $message }} @enderror</div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group @error('grade') has-error @enderror">
                <label class="control-label tab-con">{{ trans('main.question_grade') }}</label>
                <input type="number" name="grade" value="{{ !empty($question_edit) ? $question_edit->grade : '' }}" placeholder="{{ trans('main.question_grade') }}" class="form-control">
                <div class="help-block">@error('grade') {{ $message }} @enderror</div>
            </div>
        </div>
    </div>
</form>
