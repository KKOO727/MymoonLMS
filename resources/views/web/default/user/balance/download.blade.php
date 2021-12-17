<h3 class="paz no-margin violet">{{ trans('Sold Courses') }}</h3>
<hr>
    @if(count($lists) == 0)
        <div class="text-center">
            <img src="/assets/default/images/empty/sales.png">
            <div class="h-20"></div>
            <span class="empty-first-line">{{ trans('main.no_sale') }}</span>
            <div class="h-20"></div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table ucp-table" id="download-table">
                <thead class="back-orange">
                <th width="20" class="text-center">#</th>
                <th>{{ trans('main.course_title') }}</th>
                <th class="text-center" width="100">{{ trans('Student') }}</th>
                <th class="text-center" width="50">{{ trans('main.paid_amount') }}</th>
                <th class="text-center" width="50">{{ trans('Income') }}</th>

                <th class="text-center" width="200">{{ trans('main.date') }}</th>
                <th class="text-center" width="100">{{ trans('main.status') }}</th>
                <th class="text-center" width="40">{{ trans('Action') }}</th>
                </thead>
                <tbody>
                @foreach($sold as $item)
                        <tr>
                            <td class="text-center" width="20">{{ $item->id }}</td>
                            <td class="text-left">
                               @if($item->content_part_id == '0')
                                    <a href="/product/{{ $item->content_id }}" class="color-in" target="_blank">{{ get_courseName($item->content_id,false) }}</a>
                                @else
                                    <a href="/product/{{ $item->content_id }}" class="color-in" target="_blank">{{ get_courseName($item->content_part_id,true) }}</a>
                                @endif
                            </td>
                            <td class="text-center">
                                    {{ get_username($item->buyer_id) }}
                            </td>

                            <td class="text-center">

                                    {{ currency($item->transaction->price) }}

                            </td>
                            <td class="text-center">

                                {{ currency($item->transaction->seller_income) }}

                            </td>

                            <td class="text-center" width="150">{{ date('d F Y',$item->created_at) }}</td>
                            <td class="text-center" width="100">
                                @if($item->type=="download")
                                    <b class="green-s">{{ trans('main.successful') }}</b>
                                @else
                                    @if($item->post_feedback == null)
                                        <b>{{ trans('Ongoing') }}</b>
                                    @elseif($item->post_feedback == 1)
                                        <b class="green-s">{{ trans('main.successful') }}</b>
                                    @elseif($item->post_feedback == 2 || $item->post_feedback == 3)
                                        <b class="red-s">{{ trans('main.failed') }}</b>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->type == 'post')
                                    <a class="gray-s" href="#" data-toggle="modal" data-target="#post{{ $item->id }}" title="More info"><span class="crticon mdi mdi-package"></span></a>
                                @else
                                    #
                                @endif
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
                                            <p> {{ trans('main.tracking_code') }}: <strong>@if($item->post_code == null or $item->post_code == '') {!! '<b class="red-s">Parcel not sent yet.</b>' !!} @else {{ $item->post_code }} @endif</strong></p>
                                            <br>
                                            <p>  {{ trans('main.shipping_date') }} <strong>@if(is_numeric($item->post_code_date)) {{ date('d F Y | H:i',$item->post_code_date) }} @endif</strong></p>
                                            <br>
                                            <p> {{ trans('main.address') }}: <strong>{{ userAddress($item->buyer_id) }}</strong></p>
                                        </div>
                                        <div class="modal-footer">
                                            <span class="pull-right star-rate-text">{{ trans('main.feedback') }}</span>&nbsp;
                                            <span class="pull-right star-rate" data-score="{{ !empty($item->rate) ? $item->rate->rate : 0 }}" disabled=""></span>
                                            <button type="button" class="btn btn-custom" data-dismiss="modal">{{ trans('main.close') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

