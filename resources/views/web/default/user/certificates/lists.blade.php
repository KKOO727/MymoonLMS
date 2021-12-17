@extends(getTemplate() . '.user.layout_user.quizzes')

@section('tab2','active')

@section('tab')
    <div class="h-20"></div>
    <div class="off-filters-li" style="padding: 15px">
        <div class="table-responsive">
            <table class="table ucp-table" id="request-table">
                <thead class="thead-s">
                <th class="cell-ta">{{ trans('main.name') }}</th>
                <th class="text-center">{{ trans('main.course') }}</th>
                <th class="text-center">{{ trans('main.you_grade') }}</th>
                <th class="text-center">{{ trans('main.quiz_grade') }}</th>
                <th class="text-center">{{ trans('main.time_and_date') }}</th>
                <th class="text-center">{{ trans('main.controls') }}</th>
                </thead>
                <tbody>
                @foreach ($certificates as $certificate)
                    <tr>
                        <td class="text-center">{{ $certificate->quiz->name }}</td>
                        <td class="text-center">{{ $certificate->quiz->content->title }}</td>
                        <td class="text-center">{{ $certificate->user_grade }}</td>
                        <td class="text-center">{{ $certificate->quiz->pass_mark }}</td>
                        <td class="text-center">{{ date('Y-m-d | H:i', $certificate->created_at) }}</td>
                        <td class="text-center">
                            <a href="/user/certificates/{{ $certificate->id }}/download" class="btn btn-success btn-round">{{ trans('main.download') }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
