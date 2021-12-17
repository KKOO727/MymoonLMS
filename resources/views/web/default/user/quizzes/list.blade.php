@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <div class="container-fluid">
        <div class="row product-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="violet"><span class="submicon mdi mdi-text"></span>Homework</h2>
                        <h4 class="violet"><strong>Course :</strong> {{$course->title}} | <strong>Class ID</strong>
                            : {{$class->id}}</h4>
                    </div>
                </div>

                @if($user->type =='Teacher')
                    <div class="row">
                        <div class="accordion-off col-xs-12">
                            <ul id="accordion" class="accordion off-filters-li">
                                <li class="open">
                                    <div class="link">
                                        <h2>{{ !empty($quiz) ? trans('main.edit_quizzes') : trans('main.create_new_quizzes') }}</h2>
                                        <i class="mdi mdi-chevron-down"></i>
                                    </div>
                                    <ul class="submenu" style="display: block;">
                                        <div class="h-10"></div>
                                        <form
                                            action="/user/quizzes/{{ !empty($quiz) ? 'update/'.$quiz->id : 'store' }}"
                                            method="post" class="form">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="content_id" value="{{$course->id}}"/>
                                            <input type="hidden" name="class_id" value="{{$class->id}}"/>
                                            <div class="row">
                                                <div class="col-md-6 pull-left">
                                                    <div class="form-group @error('name') has-error @enderror">
                                                        <label
                                                            class="control-label tab-con">{{ trans('main.quiz_name') }}</label>
                                                        <input type="text" name="name"
                                                               value="{{ !empty($quiz) ? $quiz->name : old('name') }}"
                                                               class="form-control">
                                                        <div
                                                            class="help-block">@error('name') {{ $message }} @enderror</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pull-left">
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label tab-con">{{ trans('main.quiz_time') }}
                                                            ({{ trans('main.minute') }})</label>
                                                        <input type="number" name="time"
                                                               value="{{ !empty($quiz) ? $quiz->time : old('time') }}"
                                                               placeholder="Empty means infinity"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-md-6 pull-left">
                                                    <div class="form-group @error('pass_mark') has-error @enderror">
                                                        <label
                                                            class="control-label tab-con">{{ trans('main.quiz_pass_mark') }}</label>
                                                        <input type="number" name="pass_mark"
                                                               value="{{ !empty($quiz) ? $quiz->pass_mark : old('pass_mark') }}"
                                                               class="form-control">
                                                        <div
                                                            class="help-block">@error('pass_mark') {{ $message }} @enderror</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 pull-left">
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label tab-con">{{ trans('main.quiz_number_attempt') }}</label>
                                                        <input type="number" name="attempt"
                                                               value="{{ !empty($quiz) ? $quiz->attempt : old('attempt') }}"
                                                               placeholder="Empty means infinity"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 pull-left">
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label tab-con">{{ trans('main.status') }}</label>
                                                        <select name="status" class="form-control font-s">
                                                            <option
                                                                value="disabled" {{ (!empty($quiz) and $quiz->status == 'disabled') ? 'selected' : '' }}>{{ trans('main.disabled') }}</option>
                                                            <option
                                                                value="active" {{ (!empty($quiz) and $quiz->status == 'active') ? 'selected' : '' }}>{{ trans('main.active') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 pull-left">
                                                    <div class="form-group">
                                                        <label
                                                            class="control-label tab-con">{{ trans('main.certificate') }}</label>
                                                        <div class="switch switch-sm switch-primary"
                                                             style="width: 100%">
                                                            <input type="hidden" value="0" name="certificate">
                                                            <input type="checkbox" name="certificate" value="1"
                                                                   data-plugin-ios-switch {{ (!empty($quiz) and $quiz->certificate) ? 'checked' : '' }} />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-custom btn-primary"
                                                                style="margin-top: 20px">
                                                            <span>{{ trans('main.save_changes') }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                        <div class="h-10"></div>
                                    </ul>
                                </li>

                                @if (empty($quiz))
                                    <li class="open">
                                        <div class="link"><h2>{{ trans('main.quizzes_list') }}</h2><i
                                                class="mdi mdi-chevron-down"></i></div>
                                        <ul class="submenu dblock">
                                            <div class="h-10"></div>
                                            {{--count($lists)--}}
                                            @if(empty($quizzes))
                                                <div class="text-center">
                                                    <img src="/assets/default/images/empty/Request.png">
                                                    <div class="h-20"></div>
                                                    <span
                                                        class="empty-first-line">{{ trans('main.no_quizzes') }}</span>
                                                    <div class="h-10"></div>

                                                    <div class="h-20"></div>
                                                </div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table ucp-table" id="request-table">
                                                        <thead class="thead-s">
                                                        <th class="cell-ta">{{ trans('main.name') }}</th>
                                                        <th class="text-center">{{ trans('main.students') }}</th>
                                                        <th class="text-center">{{ trans('main.questions') }}</th>
                                                        <th class="text-center">{{ trans('main.average_grade') }}</th>
                                                        <th class="text-center">{{ trans('main.review_needs') }}</th>
                                                        <th class="text-center">{{ trans('main.status') }}</th>
                                                        <th class="text-center"
                                                            width="100">{{ trans('main.controls') }}</th>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($quizzes as $quiz)
                                                            <tr>
                                                                <td class="text-center">
                                                                    {{ $quiz->name }}
                                                                    <small
                                                                        style="display: block">({{ $quiz->content->title }}
                                                                        )</small>
                                                                </td>
                                                                <td class="text-center">{{ count($quiz->QuizResults) }}</td>
                                                                <td class="text-center">{{ count($quiz->questions) }}</td>
                                                                <td class="text-center">{{ $quiz->average_grade }}</td>
                                                                <td class="text-center">{{ $quiz->review_needs }}</td>
                                                                <td class="text-center">
                                                                    @if($quiz->status == 'active')
                                                                        <b class="c-g">{{ trans('admin.active') }}</b>
                                                                    @else
                                                                        <span
                                                                            class="c-r">{{ trans('admin.disabled') }}</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center" width="250">
                                                                    <a href="/user/quizzes/edit/{{ $quiz->id }}"
                                                                       class="gray-s" data-toggle="tooltip"
                                                                       title="{{ trans('main.edit_quizzes') }}"><span
                                                                            class="crticon mdi mdi-lead-pencil"></span></a>
                                                                    <a href="/user/quizzes/{{ $quiz->id }}/questions"
                                                                       class="gray-s" data-toggle="tooltip"
                                                                       title="{{ trans('main.questions') }}">
                                                                            <span
                                                                                class="crticon mdi mdi-account-question"></span>
                                                                    </a>
                                                                    <a href="/user/quizzes/{{ $quiz->id }}/results"
                                                                       class="gray-s" data-toggle="tooltip"
                                                                       title="{{ trans('main.show_results') }}">
                                                                        <span class="crticon mdi mdi-eye"></span>
                                                                    </a>
                                                                    <button data-id="{{ $quiz->id }}"
                                                                            class="gray-s btn-transparent btn-delete-quiz"
                                                                            data-toggle="tooltip"
                                                                            title="{{ trans('main.delete') }}"><span
                                                                            class="crticon mdi mdi-delete-forever"></span>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="h-10"></div>
                                            @endif
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="h-20"></div>
                        <div class="off-filters-li" style="padding: 15px">
                            <div class="table-responsive">
                                <table class="table ucp-table" id="request-table">
                                    <thead class="thead-s">
                                    <th class="cell-ta">{{ trans('main.name') }}</th>
                                    <th class="text-center">{{ trans('main.course') }}</th>
                                    <th class="text-center">{{ trans('main.quiz_grade') }}</th>
                                    <th class="text-center">{{ trans('main.you_grade') }}</th>
                                    <th class="text-center">{{ trans('main.quiz_number_attempt') }}</th>
                                    <th class="text-center">{{ trans('main.quiz_time') }} (min)</th>
                                    <th class="text-center">{{ trans('main.time_and_date') }}</th>
                                    <th class="text-center">{{ trans('main.status') }}</th>
                                    <th class="text-center">{{ trans('main.controls') }}</th>
                                    </thead>
                                    <tbody>
                                    @foreach ($quizzes as $quiz)
                                        <tr>
                                            <td class="text-center">
                                                {{ $quiz->name }}
                                            </td>
                                            <td class="text-center">{{ $quiz->content->title }}</td>
                                            <td class="text-center">{{ (count($quiz->questionsGradeSum) > 0) ? $quiz->questionsGradeSum[0]->grade_sum : 0 }}</td>
                                            <td class="text-center">{{ (!empty($quiz->result) and isset($quiz->result)) ? $quiz->result->user_grade : 'No grade' }}</td>
                                            <td class="text-center">{{ $quiz->attempt - $quiz->result_count }}</td>
                                            <td class="text-center">{{ $quiz->time }}</td>
                                            <td class="text-center">{{ date('Y-m-d | H:i', $quiz->created_at) }}</td>
                                            <td class="text-center">
                                                @if (!empty($quiz->result) and isset($quiz->result))
                                                    @if ($quiz->result->status == 'pass')
                                                        <span
                                                            class="badge badge-success">{{ trans('main.passed') }}</span>
                                                    @elseif ($quiz->result->status == 'fail')
                                                        <span
                                                            class="badge badge-danger">{{ trans('main.failed') }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-warning">{{ trans('main.waiting') }}</span>
                                                    @endif
                                                @else
                                                    <span
                                                        class="badge badge-warning">{{ trans('main.no_term') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ ($quiz->can_try) ? '/user/quizzes/'. $quiz->id .'/start' : ''}}"
                                                   target="_blank"
                                                   {{ (!$quiz->can_try) ? 'disabled="disabled"' : '' }} class="btn btn-success btn-round">{{ trans('main.start') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="h-30"></div>
    <div id="quizzesDelete" class="modal fade" role="dialog">
        <div class="modal-dialog" style="z-index: 1050">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3>{{ trans('main.delete') }}</h3>
                </div>
                <div class="modal-body" style="max-height: 550px;overflow-y: scroll">
                    <p>{{ trans('main.quiz_delete_alert') }}</p>
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
    <script>
        $('body').on('click', '.btn-delete-quiz', function (e) {
            e.preventDefault();
            var quiz_id = $(this).attr('data-id');
            $('#quizzesDelete').modal('show');
            $('#quizzesDelete').find('.delete').attr('href', '/user/quizzes/delete/' + quiz_id);
        })
    </script>
@endsection
