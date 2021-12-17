@extends(getTemplate().'.user.layout.articlelayout')

@section('tab2','active')
@section('tab')
    <div class="h-20"></div>
    @if(count($lists) == 0)
        <div class="text-center">
            <img src="/assets/default/images/empty/articles.png">
            <div class="h-20"></div>
            <span class="empty-first-line">{{ trans('main.no_article') }}</span>
            <div class="h-10"></div>
            <span class="empty-second-line">
                <span>{{ trans('main.article_desc') }}</span>
            </span>
            <div class="h-20"></div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table ucp-table" id="article-table">
                <thead class="thead-s">
                <th class="cell-ta">{{ trans('main.title') }}</th>
                <th class="text-center" width="150">{{ trans('main.category') }}</th>
                <th class="text-center" width="150">{{ trans('main.date') }}</th>
                <th class="text-center" width="150">{{ trans('main.status') }}</th>
                <th class="text-center" width="100">{{ trans('main.controls') }}</th>
                </thead>
                <tbody>
                @foreach($lists as $item)
                    <tr>
                        <td class="cell-ta">{{ $item->title }}</td>
                        <td class="text-center">
                            @if (!empty($item->category))
                                {{ $item->category->title }}
                            @endif
                        </td>
                        <td class="text-center" width="150">{{ date('d F Y | H:i',$item->created_at) }}</td>
                        <td class="text-center">
                            @if($item->mode == 'publish')
                                <b class="green-s">{{ trans('main.published') }}</b>
                            @elseif($item->mode == 'draft')
                                <b class="orange-s">{{ trans('main.draft') }}</b>
                            @elseif($item->mode == 'request')
                                <b class="blue-s">{{ trans('main.send_for_review') }}</b>
                            @elseif($item->mode == 'delete')
                                <b class="red-s">{{ trans('main.unpublish_request') }}</b>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="/user/article/edit/{{ $item->id }}" title="Edit"><span class="crticon mdi mdi-lead-pencil"></span></a>
                            <a href="/user/article/delete/{{ $item->id }}" title="Unpublish Request"><span class="crticon mdi mdi-delete-forever"></span></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
