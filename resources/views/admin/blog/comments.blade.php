@extends('admin.newlayout.layout',['breadcom'=>['Blog','Comments']])
@section('title')
    {{ trans('admin.blog_comments') }}
@endsection
@section('page')

    <section class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th>{{ trans('admin.text') }}</th>
                    <th class="text-center" width="120">{{ trans('admin.username') }}</th>
                    <th class="text-center">{{ trans('admin.th_status') }}</th>
                    <th class="text-center" width="200">{{ trans('admin.post') }}</th>
                    <th class="text-center" width="200">{{ trans('admin.created_date') }}</th>
                    <th class="text-center" width="150">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($comments as $item)
                    <tr>
                        <td>{!! $item->comment !!}</td>
                        <td class="text-center">
                            @if (!empty($item->user))
                                <a target="_blank" href="javascript:void(0);">{{ $item->user->name }}</a>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->mode == 'publish')
                                <b class="c-g">{{ trans('admin.published') }}</b>
                            @else
                                <b class="c-o">{{ trans('admin.waiting') }}</b>
                            @endif
                        </td>
                        <td class="text-center">{!! date('d F Y / H:i',$item->created_at) !!}</td>
                        <td class="text-center"><a href="/admin/blog/post/edit/{{ $item->post->id ?? '' }}">{{ $item->post->title ?? '' }}</a></td>
                        <td class="text-center">
                            <a href="/admin/blog/comment/reply/{{ $item->id }}" title="Reply"><i class="fa fa-reply" aria-hidden="true"></i></a>
                            <a href="/admin/blog/comment/edit/{{ $item->id }}" title="Edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a href="#" data-href="/admin/blog/comment/delete/{{ $item->id }}" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                            @if($item->mode == 'publish')
                                <a href="/admin/blog/comment/view/draft/{{ $item->id }}/" title="Add to waiting list"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                            @else
                                <a href="/admin/blog/comment/view/publish/{{ $item->id }}/" title="Approve Comment"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection
