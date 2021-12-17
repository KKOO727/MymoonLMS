@extends(getTemplate() . '.user.layout.layout')

@section('pages')

    <div class="container-fluid">
        <div class="container">
            <div class="h-20"></div>
            <div class="col-md-6 col-xs-12 tab-con">
                <div class="ucp-section-box">
                    <div class="header back-red">{{ trans('main.edit_channel') }}</div>
                    <div class="body">
                        <form method="post" action="/user/channel/edit/store/{{ $edit->id }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label" for="inputDefault">{{ trans('main.title') }}</label>
                                <input type="text" name="title" value="{{ $edit->title }}" class="form-control" id="inputDefault" required>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="inputDefault">{{ trans('main.link') }}</label>
                                <input type="text" value="{{ $edit->username }}" class="form-control" id="inputDefault" disabled="disabled">
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{ trans('main.cover') }}</label>
                                <div class="input-group" style="display: flex">
                                    <button type="button" data-input="image" data-preview="holder" class="lfm_load btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="image" class="form-control" value="{{ $edit->image ?? '' }}" type="text" name="image" dir="ltr" >
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="image">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{ trans('main.icon') }}</label>
                                <div class="input-group" style="display: flex">
                                    <button type="button" data-input="avatar" data-preview="holder" class="lfm_load btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="avatar" class="form-control" value="{{ $edit->avatar ?? '' }}" type="text" name="avatar" dir="ltr" >
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="avatar">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">{{ trans('main.description') }}</label>
                                <textarea class="form-control" name="description">{!!  $edit->description !!}</textarea>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-custom pull-left">{{ trans('main.save_changes') }}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12 tab-con">
                <div class="table-responsive">
                    <table class="table ucp-table">
                        <thead class="back-blue">
                        <th class="text-center">{{ trans('main.title') }}</th>
                        <th class="text-center">{{ trans('main.link') }}</th>
                        <th class="text-center">{{ trans('main.views') }}</th>
                        <th class="text-center">{{ trans('main.status') }}</th>
                        <th class="text-center">{{ trans('main.controls') }}</th>
                        </thead>
                        <tbody>
                        @foreach($channels as $channel)
                            <tr>
                                <td class="text-center">{{ $channel->title }}</td>
                                <td class="text-center"><a href="/chanel/{{ $channel->username }}">{{ $channel->username }}</a></td>
                                <td class="text-center">{{ $channel->view }}</td>
                                <td class="text-center">
                                    @if($channel->mode == null or $channel->mode == 'pending' or $channel->mode == 'draft')
                                        <b class="orange-s">{{ trans('main.waiting') }}</b>
                                    @else
                                        <b class="green-s">{{ trans('main.active') }}</b>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="/user/channel/video/{{ $channel->id }}" title="Add video to channel"><span class="crticon mdi mdi-file-video"></span></a>
                                    <a href="#" data-href="/user/channel/delete/{{ $channel->id }}" data-toggle="modal" data-target="#confirm-delete"><span class="crticon mdi mdi-delete-forever"></span></a>
                                    <a href="/user/channel/edit/{{ $channel->id }}"><span class="crticon mdi mdi-lead-pencil"></span></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('.lfm_load').filemanager('file',{prefix: '/user/laravel-filemanager'});
        $('#channel-hover').addClass('item-box-active');
    </script>
@endsection
