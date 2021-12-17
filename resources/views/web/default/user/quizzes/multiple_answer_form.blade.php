<div class="{{ (empty($answer) or (!empty($loop) and $loop->iteration == 1)) ? 'main-row-answer' : '' }} answer-box" style="display: flex;align-items: center">
    <div class="col-md-12 answer-card">

        <div class="row">
            <div class="col-md-9 pull-left">
                <div class="form-group">
                    <label class="control-label tab-con">{{ trans('main.answer') }}</label>
                    <button type="button" class="btn btn-xs remove-btn btn-danger pull-right {{ !empty($answer) ? 'show' : '' }}" style="margin-bottom: 12px"><i class="mdi mdi-close"></i></button>

                    <input type="text" name="answers[{{ (empty($answer) or (!empty($loop) and $loop->iteration == 1)) ? 'record' : $answer->id }}][title]" value="{{ !empty($answer) ? $answer->title : '' }}" placeholder="{{ trans('main.add_answer') }}" class="form-control">
                    <div class="help-block"></div>
                </div>
                <div class="form-group" style="display:none">
                    <label class="control-label">{{ trans('main.images') }}</label>
                    <div style="display: flex">
                        <button type="button" data-input="answer_image" data-preview="holder" class="lfm-btn btn btn-primary">
                            Choose
                        </button>
                        <input name="answers[{{ (empty($answer) or (!empty($loop) and $loop->iteration == 1)) ? 'record' : $answer->id }}][image]" value="{{ !empty($answer) ? $answer->image : '' }}" id="answer_image" class="form-control lfm-input" dir="ltr" type="text">
                    </div>
                </div>
            </div>

            <div class="col-md-3 pull-left">
                <div class="form-group">
                    <label class="control-label tab-con">{{ trans('main.correct') }}</label>
                    <div class="switch switch-sm switch-primary" style="width: 100%">
                        <input type="hidden" class="correct-toggle" value="0" name="answers[{{ (empty($answer) or (!empty($loop) and $loop->iteration == 1)) ? 'record' : $answer->id }}][correct]">
                        <input type="checkbox" class="correct-toggle" name="answers[{{ (empty($answer) or (!empty($loop) and $loop->iteration == 1)) ? 'record' : $answer->id }}][correct]" @if(!empty($answer) and $answer->correct) checked @endif value="1" data-plugin-ios-switch/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
