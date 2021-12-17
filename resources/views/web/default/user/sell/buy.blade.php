@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <div class="h-20"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="leftMenu">
                    <ul>
                        <li><a href="{{URL::to('/user/video/buy')}}"><i class="fas fa-book"></i> My Courses</a></li>
                        <li><a href="{{URL::to('/user/favourites')}}"><i class="fas fa-heart"></i> My Favourite List</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="light-box">
                    <div class="h-20"></div>
                    @if(count($list) == 0)
                        <div class="text-center">
                            <img src="/assets/default/images/empty/dashboardbought.png">
                            <div class="h-20"></div>
                            <span class="empty-first-line">{{ trans('main.not_purchased_item') }}</span>
                            <div class="h-20"></div>
                        </div>
                    @else

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="table-responsive">
                                    <table class="table ucp-table" id="buy-table">
                                        <thead class="thead-s">
                                        <tr>
                                            <th class="text-center">{{ trans('main.item_no') }}</th>
                                            <th class="cell-ta">{{ trans('main.title') }}</th>
                                            <th class="cell-ta">{{ trans('main.vendor') }}</th>
                                            <th class="text-center">{{ trans('main.price') }}</th>
                                            <th class="text-center">{{ trans('main.pur_date') }}</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">{{ trans('main.controls') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($list as $item)
                                            @if(isset($item->content))
                                                <tr class="text-center">
                                                    <td class="text-center">{{ $item->id }}</td>
                                                    <td class="cell-ta"><a href="/product/{{$item->content_id}}">
                                                            @if($item->content_part_id != '0' && $item->content_part_id !== null)
                                                                {{get_courseName($item->content_part_id,true)}}
                                                            @else
                                                                {{ $item->content->title ?? '' }}
                                                            @endif
                                                        </a></td>
                                                    <td class="cell-ta"><a href="/profile/{{ $item->seller_id ?? '' }}">{{ get_username($item->seller_id) }}</a>
                                                    </td>
                                                    <td>{{ currencySign() }}{{ $item->transaction->price }}</td>
                                                    <td>{{ date('Y/m/d',$item->created_at) }}</td>
                                                    <td>
                                                        @if($item->status == 'ongoing')
                                                            <span class="badge badge-warnig">Ongoing</span>
                                                        @elseif($item->status == 'completed')
                                                            <span class="badge badge-success">Completed</span>
                                                        @elseif($item->status == 'ongoing_pending_payment')
                                                            <span class="badge badge-danger">Pending Renewal</span><br>
                                                        @else
                                                            {{' '}}
                                                        @endif

                                                    </td>
                                                    <td>
                                                        @if($item->status == 'ongoing_pending_payment')
                                                            <a class="btn btn-sm btn-danger" target="_blank"
                                                               href="/user/book/renewPlan?id={{ $item->id }}"
                                                               title="Renew Course"> Renew Course</a>
                                                    @endif
                                                    <!--<a class="gray-s" target="_blank" href="/user/video/buy/print/{{ $item->id }}/" title="View invoice"><span class="crticon mdi mdi-printer"></span></a>-->
                                                    </td>
                                                </tr>
                                            @endif

                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                        </div>
                </div>
                <div class="h-25"></div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $('.star-rate').raty({
            starType: 'i',
            score: function () {
                return $(this).attr('data-score');
            },
            click: function (rate) {
                var id = $(this).attr('data-id');
                $.get('/user/video/buy/rate/' + id + '/' + rate, function (data) {
                    if (data == 0) {
                        $.notify({
                            message: 'Sorry feedback not send. Try again.'
                        }, {
                            type: 'danger',
                            allow_dismiss: false,
                            z_index: '99999999',
                            placement: {
                                from: "bottom",
                                align: "right"
                            },
                            position: 'fixed'
                        });
                    }
                    if (data == 1) {
                        $('.btn-submit-confirm').removeAttr('disabled');
                        $.notify({
                            message: 'Your feedback sent successfully.'
                        }, {
                            type: 'danger',
                            allow_dismiss: false,
                            z_index: '99999999',
                            placement: {
                                from: "bottom",
                                align: "right"
                            },
                            position: 'fixed'
                        });
                    }
                })
            }
        });
    </script>
@endsection
