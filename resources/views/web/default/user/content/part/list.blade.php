@extends(getTemplate().'.user.layout.partlayout')

@section('tab2','active')
@section('tab')
    <div class="h-20"></div>
    <div class="table-responsive">
        <table class="table ucp-table">
            <thead class="thead-s">
            <th>{{ trans('main.title') }}</th>
            <th>{{ trans('main.description') }}</th>
            <th class="text-center" width="150">{{ trans('main.volume') }}</th>
            <th class="text-center" width="150">{{ trans('main.duration') }}</th>
            <th class="text-center" width="150">{{ trans('main.date') }}</th>
            <th class="text-center" width="50">{{ trans('main.status') }}</th>
            <th class="text-center" width="100">{{ trans('main.controls') }}</th>
            </thead>
            <tbody>
            @foreach($lists as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{!! $item->description !!}</td>
                    <td class="text-center">{{ $item->size }}{{ trans('main.mb') }} </td>
                    <td class="text-center">{{ $item->duration }}{{ trans('main.minute') }} </td>
                    <td class="text-center" width="150">{{ date('d F Y | H:i',strtotime($item->created_at)) }}</td>
                    <td class="text-center">
                        @if($item->mode == 'publish')
                            <b class="blue-s">{{ trans('main.publish') }}</b>
                        @elseif($item->mode == 'draft')
                            <b class="orange-s">{{ trans('main.draft') }}</b>
                        @elseif($item->mode == 'request')
                            <span class="green-s">{{ trans('main.waiting') }}</span>
                        @elseif($item->mode == 'delete')
                            <span class="red-s">{{ trans('main.unpublish_request') }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="/user/content/part/edit/{{ $item->id }}" title="Edit" class="gray-s"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        <a href="/user/content/part/delete/{{ $item->id }}" title="Delete" class="gray-s"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        <a href="/user/content/part/request/{{ $item->id }}" title="Add to waiting" class="gray-s"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                        <a href="/user/content/part/draft/{{ $item->id }}" title="Draft" class="gray-s"><i class="fa fa-minus-square" aria-hidden="true"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
