@extends(getTemplate() . '.user.layout.layout')

@section('pages')
    <div class="container-fluid">
        <div class="container">
            <div class="h-20"></div>
            <div class="col-md-6 col-xs-12 tab-con">
                <div class="ucp-section-box">
                    <div class="header back-red">{{ trans('main.add_content_to_channel') }}</div>
                    <div class="body">
                        <form method="post" action="/user/channel/video/store/{{ $chanel->id }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="control-label">{{ trans('main.content') }}</label>
                                <select name="content_id" class="form-control font-s">
                                    @foreach($userContents as $uc)
                                        <option value="{{ $uc->id }}">{{ $uc->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-custom pull-left" value="Save Changes">{{ trans('main.save_changes') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xs-12 tab-con">
                <div class="table-responsive">
                    <table class="table ucp-table">
                        <thead class="back-blue">
                        <th>{{ trans('main.course_title') }}</th>
                        <th class="text-center" width="50">{{ trans('main.controls') }}</th>
                        </thead>
                        <tbody>
                        @foreach($chanel->contents as $content)
                            <tr>
                                <td>{{ $content->content->title }}</td>
                                <td class="text-center" width="50">
                                    <a href="#" data-href="/user/channel/video/delete/{{ $content->id }}" data-toggle="modal" data-target="#confirm-delete" title="Remove video"><span class="crticon mdi mdi-delete-forever"></span></a>
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
    <script>$('#channel-hover').addClass('item-box-active');</script>
@endsection
