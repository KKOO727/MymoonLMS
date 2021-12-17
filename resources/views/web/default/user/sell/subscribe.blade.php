@extends($user['vendor'] == 1 ? getTemplate() . '.user.layout.balancelayout' : getTemplate() . '.user.layout_user.videolayout')
@section('tab2','active')
@section('tab')
    <div class="h-20"></div>
    @if(count($list) == 0)
        <div class="text-center">
            <img src="/assets/default/images/empty/dashboardbought.png">
            <div class="h-20"></div>
            <span class="empty-first-line">{{ trans('main.not_purchased_item') }}</span>
            <div class="h-20"></div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table ucp-table" id="buy-table">
                <thead class="thead-s">
                <th class="text-center" width="80">{{ trans('main.item_no') }}</th>
                <th class="cell-ta">{{ trans('main.title') }}</th>
                <th class="cell-ta">{{ trans('main.category') }}</th>
                <th class="cell-ta">{{ trans('main.vendor') }}</th>
                <th class="text-center">{{ trans('main.delivery_type') }}</th>
                <th class="text-center" width="100">{{ trans('main.price') }}</th>
                <th class="text-center" width="150">{{ trans('main.pur_date') }}</th>
                <th class="text-center" width="100">{{ trans('main.controls') }}</th>
                </thead>
                <tbody>
                @foreach($list as $item)
                    <tr class="text-center">
                        <td class="text-center">{{ $item->id }}</td>
                        <td class="cell-ta"><a href="/product/{{ $item->content->id }}">{{ $item->content->title }}</a></td>
                        <td class="cell-ta"><a href="/category/{{ $item->content->category->class }}">{{ $item->content->category->title }}</a></td>
                        <td class="cell-ta"><a href="/profile/{{ $item->content->user->id }}">{{ !empty($item->content->user->name) ? $item->content->user->name : $item->content->user->username }}</a></td>
                        <td>
                            @if($item->type == "download")
                                <span class="green-s">{{ trans('main.download') }}</span>
                            @elseif($item->type == 'subscribe')
                                <span class="blue-s">{{ trans('main.subscribe') }}</span>
                            @else
                                <span class="blue-s">{{ trans('main.postal') }}</span>
                            @endif
                        </td>
                        <td>{{ currencySign() }}{{ $item->transaction->price }}</td>
                        <td>
                            {{ date('Y/m/d',$item->created_at) }}
                            @if($item->type == 'subscribe')
                                <br><span style="color: red;">{{ date('Y/m/d',$item->remain_time) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($item->type == 'subscribe')

                            @endif
                            @if($item->type == 'post')
                                <a class="gray-s" href="#" data-toggle="modal" data-target="#post{{ $item->id }}" title="Shipping Detail"><span class="crticon mdi mdi-package"></span></a>
                            @endif
                            <a class="gray-s" href="/product/{{ $item->content->id }}" title="Download"><span class="crticon mdi mdi-arrow-down-thick"></span></a>
                            <a class="gray-s" href="/product/{{ $item->content->id }}#blog-comment-scroll" title="Leave comment"><span class="crticon mdi mdi-comment-plus"></span></a>
                            <a class="gray-s" target="_blank" href="/user/video/buy/print/{{ $item->id }}/" title="View invoice"><span class="crticon mdi mdi-printer"></span></a>
                        </td>
                    </tr>
                    <div class="modal fade" id="post{{ $item->id }}">
                        <div class="modal-dialog">
                            <form class="form form-horizontal" method="post" action="/user/video/buy/confirm/{{ $item->id }}">
                                {{ csrf_field() }}
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title">{{ trans('main.shipping_detail') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p> {{ trans('main.tracking_code') }} <strong>@if($item->post_code == null or $item->post_code == ''){!! '<b class="red-s">Package not sent yet.</b>' !!} @else {{ $item->post_code }} @endif</strong></p>
                                        <br>
                                        <p>  {{ trans('main.shipping_date') }} <strong>@if(is_numeric($item->post_code_date)) {{ date('d F Y | H:i',$item->post_code_date) }} @endif</strong></p>
                                        <br>
                                        <div class="form-group">
                                            <label class="control-label"> {{ trans('main.description') }} </label>
                                            @if($item->post_confirm == '')
                                                <textarea name="post_confirm" rows="4" class="form-control" required></textarea>
                                            @else
                                                <strong class="green-s"> {{ $item->post_confirm }} </strong>
                                            @endif
                                        </div>
                                        @if($item->post_feedback == null)
                                            <div class="form-group">
                                                <label><input type="radio" name="post_feedback" value="1" class="val-mid">&nbsp;{{ trans('main.received_nop') }}</label>
                                                <label class="lab-e"><input name="post_feedback" type="radio" value="2" class="val-mid">&nbsp;{{ trans('main.received_problem') }}</label>
                                                <label class="lab-n"><input name="post_feedback" type="radio" value="3" class="val-mid">&nbsp;{{ trans('main.not_received') }}</label>
                                            </div>
                                        @else
                                            @if($item->post_feedback == 1)
                                                <label>{{ trans('main.received_nop') }}</label>
                                            @endif
                                            @if($item->post_feedback == 2)
                                                <label>{{ trans('main.received_problem') }}</label>
                                            @endif
                                            @if($item->post_feedback == 3)
                                                <label>{{ trans('main.not_received') }}</label>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <span class="pull-right star-rate-text">{{ trans('main.feedback') }}</span>&nbsp;
                                        <span class="pull-right star-rate" data-id="{{ $item->id }}" data-score="{{ $item->rate->rate }}"></span>
                                        <button type="button" class="btn btn-custom" data-dismiss="modal">{{ trans('main.close') }}</button>
                                        @if($item->post_confirm == '')
                                            <button type="submit" class="btn btn-custom btn-submit-confirm" title="Please submit feedback before confirmation." disabled>{{ trans('main.confirm') }}</button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
                </tbody>
            </table>
            @if($user['vendor'] == 0 and get_option('default_customer_dashboard_cover') != '')
                <a href="/category"><img src="{!! get_option('default_customer_dashboard_cover') !!}" class="img-responsive"></a>
            @endif
        </div>
    @endif
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
