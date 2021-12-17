@extends($user['vendor'] == 1 ? getTemplate() . '.user.layout.videolayout' : getTemplate() . '.user.layout_user.quizzes')

@if($user['vendor'] == 1)
    @section('tab7','active')
@else
    @section('tab1','active')
@endif

@section('tab')
    <div class="accordion-off col-xs-12">
        <ul id="accordion" class="accordion off-filters-li">
            <li class="open">
                <div class="link"><h2>{{ trans('main.user_list') }}</h2><i class="mdi mdi-chevron-down"></i></div>
                <ul class="submenu dblock">
                    <div class="h-10"></div>
                    <div class="table-responsive">
                        <table class="table ucp-table" id="request-table">
                            <thead class="thead-s">
                            <th class="cell-ta">{{ trans('main.name') }}</th>
                            <th class="text-center">{{ trans('main.email') }}</th>
                            </thead>
                            <tbody>
                                @foreach($list as $item)
                                    <td class="cell-ta">{!! $item->buyer->name ?? '' !!}</td>
                                    <td class="text-center">{!! $item->buyer->email ?? '' !!}</td>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="h-10"></div>
                </ul>

            </li>
        </ul>
    </div>
@stop
