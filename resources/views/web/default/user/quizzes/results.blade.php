@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <div class="h-20"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="violet"><span class="submicon mdi mdi-text"></span>Quiz Results</h2>
                <h4 class="violet"><strong>Course :</strong> {{$quiz->content_id}} | <strong>Class ID</strong>
                    : {{$quiz->class_id}}</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="off-filters-li">
                    <div class="result-info">
                        <div class="result-info-item">
                            <strong>{{ $quiz->name }}</strong>
                            <span>{{ $quiz->content->title }}</span>
                        </div>

                        <div class="result-info-item">
                            <strong>{{ trans('main.total_results') }}</strong>
                            <span>{{ count($QuizResults) }}</span>
                        </div>

                        <div class="result-info-item">
                            <strong>{{ trans('main.waiting_results') }}</strong>
                            <span>{{ $waitingResults }}</span>
                        </div>

                        <div class="result-info-item">
                            <strong>{{ trans('main.passed') }}</strong>
                            <span>{{ $passedResults }}</span>
                        </div>

                        <div class="result-info-item">
                            <strong>{{ trans('main.average') }}</strong>
                            <span>{{ $averageResults }}</span>
                        </div>
                    </div>
                    @if(count($QuizResults) == 0)
                        <div class="text-center">
                            <img src="/assets/default/images/empty/Videos.png">
                            <div class="h-20"></div>
                            <span class="empty-first-line">{{ trans('main.no_quiz_result') }}</span>
                            <div class="h-20"></div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table ucp-table" id="content-table">
                                <thead class="thead-s">
                                <th class="text-center">{{ trans('main.student') }}</th>
                                <th class="text-center">{{ trans('main.grade') }}</th>
                                <th class="text-center">{{ trans('main.status') }}</th>
                                <th class="text-center">{{ trans('main.time_and_date') }}</th>
                                <th class="text-center" width="100">{{ trans('main.controls') }}</th>
                                </thead>
                                <tbody>
                                @foreach($QuizResults as $result)
                                    <tr>
                                        <td>{{ $result->student->name }}</td>
                                        <td>
                                            @if ($result->status == 'waiting')
                                                <span style="color: orange;font-size: 28px">-</span>
                                            @else
                                                {{ $result->user_grade }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($result->status == 'pass')
                                                <span class="badge badge-success">{{ trans('main.passed') }}</span>
                                            @elseif ($result->status == 'fail')
                                                <span class="badge badge-danger">{{ trans('main.failed') }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ trans('main.waiting') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ date('Y-m-d | H:i', $result->created_at) }}</td>
                                        <td>
                                            @if ($quiz->hasDescriptive)
                                                <button data-id="{{ $result->id }}" class="gray-s btn-transparent review-need" data-toggle="tooltip" title="{{ trans('main.review_needs') }}"><span class="crticon mdi mdi-eye"></span></button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    <div class="h-20"></div>
                </div>

                <div id="resultReview" class="modal fade" role="dialog">
                    <div class="modal-dialog" style="z-index: 1050">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>{{ trans('main.review_needs') }}</h3>
                            </div>
                            <div class="modal-body" style="max-height: 550px;overflow-y: scroll">
                                <form action="/user/quizzes/results/reviewed" method="post" id="resultReviewForm">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="result_id" class="js_result_id" value="">
                                    <div class="items"></div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="reviewSubmit" class="btn btn-custom">{{ trans('main.save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>

    <div class="h-20"></div>
@endsection

@section('script')
    <script>
        $('body').on('click', '.review-need', function (e) {
            e.preventDefault();
            var $loading = '<div class="text-center"><img src="/assets/default/images/loading.gif"/></div>';
            var result_id = $(this).attr('data-id');
            var modal = $('#resultReview');
            $('.js_result_id').val(result_id);

            modal.modal('show');
            modal.find('.modal-footer').addClass('hidden');
            modal.find('.modal-body #resultReviewForm .items').html($loading);

            var data = {
                _token: '{{ csrf_token() }}',
                result_id: result_id
            };

            $.post('/user/quizzes/results/get_descriptive', data, function (result) {
                if (result && result.data.length) {
                    var html = '';
                    for (var i = 0; i < result.data.length; i++) {
                        var item = result.data[i];
                        var value = '';
                        if (item.result_status !== 'waiting') {
                            value = item.result_grade;
                        }

                        html += '<div class="result-review">\n' +
                            '<h3>' + item.question + '</h3>\n';
                        if (item.answer !== '') {
                            html += '<div class="student-answer">\n' +
                                item.answer +
                                '</div>\n' +
                                '<input type="text" name="review[' + item.question_id + '][grade]"  placeholder="import grade (Question grade: ' + item.question_grade + ')" value="' + value + '" class="grade-input form-control"/>\n';
                        } else {
                            html += '<div class="">(no answer)</div>\n' +
                                '<input type="hidden" name="review[' + item.question_id + '][grade]" value="0" class="grade-input form-control"/>\n';
                        }

                        html += '</div>';
                    }

                    modal.find('.modal-body #resultReviewForm .items').html(html);
                    modal.find('.modal-footer').removeClass('hidden');
                } else {
                    modal.find('.modal-body #resultReviewForm .items').html('The user left the quiz and no result was saved');
                }
            }).fail((err) => {
                modal.modal('show');
                modal.find('.modal-body #resultReviewForm .items').html('No item');
            })
        })

        $('body').on('click', '#reviewSubmit', function (e) {
            e.preventDefault();
            var form = $('#resultReviewForm');
            var submit = true;
            /*form.find('.grade-input').each(function (index, input) {
                if ($(input).val() == '' || $(input).val() == null) {
                    $(input).next().text('this field is required');
                    submit = false;
                } else {
                    $(input).next().text('')
                }
            })*/

            if (submit) {
                form.submit();
            }
        });
    </script>
@endsection
