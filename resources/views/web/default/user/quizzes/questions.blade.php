@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <div class="container-fluid">
        <div class="row product-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="violet"><span class="submicon mdi mdi-text"></span>Homework</h2>
                        <h4 class="violet"><strong>Course :</strong> {{$course->title}} | <strong>Class ID</strong>: {{$class->id}}</h4>
                    </div>
                </div>

                <div class="h-20"></div>
                <div class="row">
                    <div class="col-xs-12 tab-con">
                        <div class="ucp-section-box">
                            <div class="header back-red" style="display: flex;align-items: center;justify-content: space-between">
                                <div>
                                    <h3>{{ $quiz->name }}</h3>
                                    <span>({{ $quiz->content->title }})</span>
                                </div>

                                <div>
                                    <button type="button" class="btn btn-success btn-round "
                                            id="addMultipleChoice">{{ trans('main.multiple_choice') }}</button>
                                    <button type="button" class="btn btn-custom "
                                            id="descriptiveQuestionBtn">{{ trans('main.descriptive_question') }}</button>
                                    <button type="button" class="btn btn-custom btn-primary"
                                            id="audioQuestionBtn">{{ trans('Audio/File Question') }}</button>

                                </div>
                            </div>
                            <div class="body">
                                @if (empty($quiz->questions) or count($quiz->questions) < 1)
                                    <div class="text-center">
                                        <img src="/assets/default/images/empty/Request.png">
                                        <div class="h-20"></div>
                                        <span class="empty-first-line">{{ trans('main.no_questions') }}</span>
                                        <div class="h-30"></div>
                                    </div>
                                @else
                                    <div class="questions-lists">
                                        @foreach ($quiz->questions as $question)
                                            <div class="question-item">
                                                <div>
                                                    <strong>
                                                        {{$loop->iteration .' - '. $question->title }}
                                                    </strong>
                                                    <small>({{ trans('main.grade') .' = '. (!empty($question->grade) ? $question->grade : 0) }}
                                                        , {{ $question->type }})</small>
                                                </div>

                                                <div>
                                                    <button data-id="{{ $question->id }}"
                                                            data-type="{{ $question->type }}"
                                                            class="gray-s btn-transparent btn-question-edit"
                                                            data-toggle="tooltip"
                                                            title="{{ trans('main.edit_question') }}">
                                                        <span class="crticon mdi mdi-lead-pencil"></span>
                                                    </button>
                                                    <button data-id="{{ $question->id }}"
                                                            class="btn-transparent btn-delete-question"
                                                            data-toggle="tooltip" title="{{ trans('main.delete') }}">
                                                        <span class="crticon mdi mdi-delete-forever"></span></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="multipleChoice" class="modal fade" role="dialog">
        <div class="modal-dialog" style="z-index: 1050">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3>{{ trans('main.multiple_choice') }}</h3>
                </div>
                <div class="modal-body" style="max-height: 550px;overflow-y: scroll">
                    @include(getTemplate() .'.user.quizzes.multiple_question_form')
                </div>
                <div class="modal-footer">
                    <button type="button" id="multipleAnswerSubmit"
                            class="btn btn-custom">{{ trans('main.save') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="descriptiveQuestion" class="modal fade" role="dialog">
        <div class="modal-dialog" style="z-index: 1050">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3>{{ trans('main.descriptive_choice') }}</h3>
                </div>
                <div class="modal-body" style="max-height: 550px;overflow-y: scroll">
                    @include(getTemplate() .'.user.quizzes.descriptive_question_form')
                </div>
                <div class="modal-footer">
                    <button type="button" id="descriptiveAnswerSubmit"
                            class="btn btn-custom">{{ trans('main.save') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="audioQuestion" class="modal fade" role="dialog">
        <div class="modal-dialog" style="z-index: 1050">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Audio/File</h3>
                </div>
                <div class="modal-body" style="max-height: 550px;overflow-y: scroll">
                    <form id="audioQuestionForm" action="@if (!empty($question_edit)) /user/questions/{{ $question_edit->id }}/update @else /user/quizzes/{{ $quiz->id }}/questions @endif" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="type" value="audio">
                        <div class="col-md-12">
                            <div class="form-group @error('title') has-error @enderror">
                                <label class="control-label tab-con">{{ trans('main.question_title') }}</label>
                                <input type="text" name="title" value="{{ !empty($question_edit) ? $question_edit->title : '' }}" placeholder="{{ trans('main.question_title') }}" class="form-control">
                                <div class="help-block">@error('title') {{ $message }} @enderror</div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button id="lfm_avatar" data-input="avatar" data-preview="holder" class="btn btn-dark col-md-3" style="height:40px">Choose File</button>
                                <div class="input-group col-md-9" style="display: flex">
                                    <input id="avatar" class="form-control" dir="ltr" type="text" name="file" value="">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="meta_avatar"></div>
                            </div>
                        </div>

                    </div>
                        <div class="col-md-12">
                            <div class="form-group @error('grade') has-error @enderror">
                                <label class="control-label tab-con">{{ trans('main.question_grade') }}</label>
                                <input type="number" name="grade" value="{{ !empty($question_edit) ? $question_edit->grade : '' }}" placeholder="{{ trans('main.question_grade') }}" class="form-control">
                                <div class="help-block">@error('grade') {{ $message }} @enderror</div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="audioAnswerSubmit"
                            class="btn btn-custom">{{ trans('main.save') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="questionDelete" class="modal fade" role="dialog">
        <div class="modal-dialog" style="z-index: 1050">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3>{{ trans('main.delete') }}</h3>
                </div>
                <div class="modal-body">
                    <p>{{ trans('main.question_delete_alert') }}</p>
                    <div>
                        <a href="" class=" btn btn-danger delete">
                            {{ trans('main.yes_sure') }}
                        </a>
                        <button type="button" data-dismiss="modal"
                                class="btn btn-info">{{ trans('main.cancel') }}</button>
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

    <script>
        $('body').on('click', '#audioQuestionBtn', function (e) {
            e.preventDefault();
            var modal = $('#audioQuestion');
            $('#audioQuestionForm').attr('action', '/user/quizzes/{{ $quiz->id }}/questions');
            modal.modal('show');
        })
    </script>
    <script>
        $('body .lfm-btn').filemanager('file', {prefix: '/user/laravel-filemanager'});

        $('#multipleAnswerSubmit').click(function () {
            $('#multipleAnswer').submit();
        });

        $('#descriptiveAnswerSubmit').click(function () {
            $('#descriptiveQuestionForm').submit();
        });
        $('#audioAnswerSubmit').click(function () {
            $('#audioQuestionForm').submit();
        });
        var mainRow = $('.main-row-answer');
        var copyAnswerInputs = mainRow.clone();

        $('body').on('click', '.btn-delete-question', function (e) {
            e.preventDefault();
            var question_id = $(this).attr('data-id');
            $('#questionDelete').modal('show');
            $('#questionDelete').find('.delete').attr('href', '/user/questions/' + question_id + '/delete');
        });


        var $loading = '<div class="text-center"><img src="/assets/default/images/loading.gif"/></div>';

        $('body').on('click', '.btn-question-edit', function (e) {
            e.preventDefault();
            var $this = $(this);
            var question_id = $this.attr('data-id');
            var type = $this.attr('data-type');
            var modal = $('#multipleChoice');
            if (type == 'descriptive') {
                modal = $('#descriptiveQuestion');
            }
            if (type == 'audio') {
                modal = $('#audioQuestion');
            }
            modal.modal('show');
            modal.find('.modal-footer').addClass('hidden');
            modal.find('.modal-body').html($loading);

            $.get('/user/questions/' + question_id + '/edit', function (result) {
                if (result.status) {
                    modal.find('.modal-body').html(result.html);
                    $('[data-plugin-ios-switch]').each(function () {
                        var $this = $(this);
                        $this.themePluginIOS7Switch();
                    });
                } else {
                    modal.modal('hide');
                }
                modal.find('.modal-footer').removeClass('hidden');
            })
        });

        $('body').on('click', '#addMultipleChoice', function (e) {
            e.preventDefault();
            var modal = $('#multipleChoice');
            $('#multipleAnswer').attr('action', '/user/quizzes/{{ $quiz->id }}/questions');
            modal.find('input[type="text"]').val('');
            modal.find('input[type="number"]').val('');
            modal.find('input[type="checkbox"]').prop('checked', false);
            var answerBox = modal.find('.answer-box');
            answerBox.each(function (index, box) {
                if (!$(box).hasClass('main-row-answer')) {
                    $(box).remove();
                }
            });

            modal.find('.ios-switch').removeClass('on').addClass('off');

            modal.modal('show');
            $('[data-plugin-ios-switch]').each(function () {
                var $this = $(this);
                $this.themePluginIOS7Switch();
            });
        });

        $('body').on('click', '.add-btn', function (e) {
            e.preventDefault();
            var mainRow = $('.main-row-answer');
            var copy = mainRow.clone();
            copy.removeClass('main-row-answer');
            copy.find('input[type="checkbox"]').prop('checked', false);
            copy.find('.ios-switch').remove();
            var random_id = randomString();
            copy.find('.lfm-btn').attr('data-input', random_id);
            copy.find('.lfm-input').attr('id', random_id);
            var copyHtml = copy.prop('innerHTML');
            copyHtml = copyHtml.replace(/\[record\]/g, '[' + randomString() + ']');
            copy.html(copyHtml);
            copy.find('input[type="checkbox"]').prop('checked', false);
            copy.find('input[type="text"]').val('');
            mainRow.parent().append(copy);
            $('body .lfm-btn').filemanager('file', {prefix: '/user/laravel-filemanager'});

            $('[data-plugin-ios-switch]').each(function () {
                var $this = $(this);
                $this.themePluginIOS7Switch();
            });
        });

        $('body').on('click', '.remove-btn', function (e) {
            e.preventDefault();
            $(this).closest('.answer-box').remove();
        });

        function randomString() {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

            for (var i = 0; i < 16; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));

            return text;
        }

        $('body').on('click', '.ios-switch', function () {
            var $this = $(this);
            var parent = $this.parent();
            if ($this.hasClass('on')) {
                var input = parent.find('input[type="checkbox"]');
                $('.ios-switch').each(function () {
                    var switcher = $(this);
                    var switcher_parent = switcher.parent();
                    var switcher_input = switcher_parent.find('input[type="checkbox"]');
                    switcher_input.prop('checked', false);
                    switcher.removeClass('on').addClass('off');
                });

                $this.addClass('on');
                input.prop('checked', true);
            }
        })

        $('body').on('click', '#descriptiveQuestionBtn', function (e) {
            e.preventDefault();
            var modal = $('#descriptiveQuestion');
            modal.find('input[type="text"]').val('');
            modal.find('input[type="number"]').val('');
            $('#descriptiveQuestionForm').attr('action', '/user/quizzes/{{ $quiz->id }}/questions');
            modal.modal('show');
        })

        $('body').on('click', '#audioQuestionBtn', function (e) {
            e.preventDefault();
            var modal = $('#audioQuestion');
            modal.find('input[type="text"]').val('');
            $('#audioQuestionForm').attr('action', '/user/quizzes/{{ $quiz->id }}/questions');
            modal.modal('show');
        })
    </script>

@endsection
