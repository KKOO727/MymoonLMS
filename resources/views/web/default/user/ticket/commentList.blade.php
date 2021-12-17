@extends($user['vendor'] == 1 ? getTemplate() . '.user.layout.supportlayout' : getTemplate() . '.user.layout_user.supportlayout')

@section('tab2','active')
@section('tab')
    <div class="h-20"></div>
    @if(count($lists) == 0)
        <div class="text-center">
            <img src="/assets/default/images/empty/comments.png">
            <div class="h-20"></div>
            <span class="empty-first-line">{{ trans('main.no_comment') }}</span>
            <div class="h-20"></div>
        </div>
    @else
        <div class="col-xs-12">
            <div class="row">
                <div class="table-responsive">
                    <table class="table ucp-table">
                        <thead class="back-orange">
                        <tr>
                            <th class="cell-ta">{{ trans('main.comment') }}</th>
                            <th width="400" class="text-center">{{ trans('main.course') }}</th>
                            <th width="200" class="text-center">{{ trans('main.user') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lists as $item)
                            <tr>
                                <td class="lh180 cell-ta">{{ $item->comment }}</td>
                                <td class="text-center"><a href="/product/{{ $item->content->id }}">{{ !empty($item->content->title) ? $item->content->title : 'Removed' }}</a></td>
                                <td class="text-center"><a href="/profile/{{ $item->user->id }}">{{ !empty($item->name) ? $item->name : $item->user->username }}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                @if(empty(request()->get('p')) and $count > 20)
                    <a href="?p=1" class="next-pagination pull-left"><span class="pagicon mdi mdi-chevron-left"></span></a>
                @endif
                @if(!empty(request()->get('p')) and $count>(request()->get('p') + 1) * 20)
                    <a href="?p={{ request()->get('p') + 1 }}" class="next-pagination pull-left"><span class="pagicon mdi mdi-chevron-left"></span></a>
                @endif
                @if(!empty(request()->get('p')) and request()->get('p') > 0)
                    <a href="?p={{ request()->get('p') - 1 }}" class="next-pagination pull-right"><span class="pagicon mdi mdi-chevron-right"></span></a>
                @endif
            </div>
        </div>
    @endif
@endsection
