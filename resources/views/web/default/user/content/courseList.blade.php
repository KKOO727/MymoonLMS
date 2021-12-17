<h3 class="paz no-margin violet"><i class="fas fa-book"></i> {{ trans('My Courses') }}</h3>
<hr>
    <div class="row">
        <div class="col-md-12">
            @if(count($lists) == 0)
                <div class="text-center">
                    <img src="{!! baseURL() !!}/assets/default/images/empty/Videos.png">
                    <div class="h-20"></div>
                    <span class="empty-first-line">{{ trans('main.no_course') }}</span>
                    <div class="h-10"></div>
                    <span class="empty-second-line">

            </span>
                    <div class="h-20"></div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table ucp-table" id="content-table" border='0'>
                        <thead class="thead-s">
                        <th class="text-center" width="80">{{ trans('main.item_no') }}</th>
                        <th class="text-left" width="200">{{ trans('main.title') }}</th>
                        <th class="text-center" width="50">{{ trans('main.sales') }}</th>
                        <th class="text-center" width="50">{{ trans('main.parts') }}</th>
                        <th class="text-center" width="140">{{ trans('main.category') }}</th>
                        <th class="text-center" width="100">{{ trans('main.courstype') }}</th>
                        <th class="text-center" width="150">{{ trans('main.controls') }}</th>
                        </thead>
                        <tbody>
                        @foreach($lists as $item)
                            <tr>
                                <td class="text-center" width="50">VT-{{ $item->id }}<br>{{ date('d/m/y',strtotime($item->created_at)) }}</td>
                                @if($item->mode == 'publish')
                                    <td class="text-left"><a href="{!! baseURL() !!}/product/{{ $item->id }}"
                                                             target="_blank">{{ $item->title }}</a></td>
                                @else
                                    <td class="text-left">{{ $item->title }}</td>
                                @endif
                                <td class="text-center">{{ $item->sells_count }}</td>
                                <td class="text-center">{{ $item->partsactive_count }}</td>
                                <td class="text-center">
                                    @if (!empty($item->category))
                                        <a href="{!! baseURL() !!}/category/{{ $item->category->class }}">{{ $item->category->title }}</a>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($item->type == '1')
                                        {{'One to One'}}
                                    @elseif($item->type == '2')
                                        {{'Group Course'}}
                                    @elseif($item->type == '3')
                                        {{'Video Course'}}
                                    @endif
                                    <hr class="no-margin">
                                        @if($item->mode == 'publish')
                                            <b class="green-s">{{ trans('main.published') }}</b>
                                        @elseif($item->mode == 'draft')
                                            <b class="orange-s">{{ trans('main.draft') }}</b>
                                        @elseif($item->mode == 'request')
                                            <span class="red-s">{{ trans('main.waiting') }}</span>
                                        @elseif($item->mode == 'delete')
                                            <span class="red-s">{{ trans('main.unpublish_request') }}</span>
                                        @endif
                                </td>

                                <td class="text-center">
                                    <a href="{!! baseURL() !!}/user/content/edit/{{ $item->id }}" title="Edit"
                                       class="gray-s"><span class="crticon mdi mdi-lead-pencil"></span></a>

                                    <a href="{!! baseURL() !!}/user/content/delete/{{ $item->id }}" title="Delete"
                                       class="gray-s"><span class="crticon mdi mdi-delete-forever"></span></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

