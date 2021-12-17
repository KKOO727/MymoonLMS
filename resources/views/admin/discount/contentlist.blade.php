@extends('admin.newlayout.layout',['breadcom'=>['Promotions','List']])
@section('title')
    {{ trans('admin.promotions') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th class="text-center" width="80">{{ trans('admin.start_date') }}</th>
                    <th class="text-center" width="80">{{ trans('admin.expire_date') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.type') }}</th>
                    <th class="text-center">{{ trans('admin.contents') }}</th>
                    <th class="text-center" width="200">{{ trans('admin.th_vendor') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.amount') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.th_status') }}</th>
                    <th class="text-center" width="50">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($lists as $item)
                        <tr>
                            <td class="text-center" width="80">{{ date('d F Y',$item->first_date) }}</td>
                            <td class="text-center" width="80">{{ date('d F Y',$item->last_date) }}</td>
                            <td class="text-center" width="50">
                                @if($item->type == 'content')
                                    {{ 'Single Course' }}
                                @elseif($item->type == 'category')
                                    {{ 'Category' }}
                                @elseif($item->type == 'all')
                                    {{ 'All Courses' }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->type == 'content' and !empty($item->content))
                                    <a href="/admin/content/edit/{{ $item->content->id }}">{{ $item->content->title }}</a>
                                @elseif($item->type == 'category' and !empty($item->category))
                                    <a href="/admin/content/category/edit/{{ $item->category->id }}">{{ $item->category->title }}</a>
                                @elseif($item->type == 'all')
                                    All Courses
                                @endif
                            </td>
                            <td class="text-center">{{ (!empty($item->content) and !empty($item->content->user)) ? $item->content->user->name : 'User Group' }}</td>
                            <td class="text-center" width="50">
                                    {{ $item->off }} %
                            </td>
                            <td class="text-center" width="50">
                                @if($item->mode == 'publish')
                                    <b class="c-g">{{ trans('admin.active') }}</b>
                                @elseif($item->mode == 'draft')
                                    <b class="c-o">{{ trans('admin.disabled') }}</b>
                                @endif
                                @if(time()>$item->last_date)
                                    <span class="custom-list">({{ trans('admin.expired') }})</span>
                                @endif
                            </td>
                            <td class="text-center" width="50">
                                @if($item->mode == 'draft')
                                    <a href="/admin/discount/content/publish/{{ $item->id }}" title="Publish"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                                @else
                                    <a href="/admin/discount/content/draft/{{ $item->id }}" title="Add to waiting list (Disable)"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                                @endif
                                <a href="/admin/discount/content/edit/{{ $item->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                <a href="#" data-href="/admin/discount/content/delete/{{ $item->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection



