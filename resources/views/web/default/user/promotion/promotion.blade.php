@extends(getTemplate() . '.user.layout.videolayout')

@section('tab5','active')
@section('tab')
    <div class="row">
        <div class="accordion-off col-xs-12">
            <ul id="accordion" class="accordion off-filters-li">
                <li>
                    <div class="link"><h2>{{ trans('main.promoted_courses') }}</h2><i class="mdi mdi-chevron-down"></i></div>
                    <ul class="submenu">
                        <div class="h-10"></div>
                        @if(count($list) == 0)
                            <div class="text-center">
                                <img src="/assets/default/images/empty/Promotion.png">
                                <div class="h-20"></div>
                                <span class="empty-first-line">{{ trans('main.no_promotion') }}</span>
                                <div class="h-10"></div>
                                <span class="empty-second-line">
                                <span>{{ trans('main.promotion_desc') }}</span>
                            </span>
                                <div class="h-20"></div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table ucp-table">
                                    <thead class="thead-s">
                                    <th class="text-center">{{ trans('main.plan') }}</th>
                                    <th class="text-center">{{ trans('main.description') }}</th>
                                    <th class="text-center">{{ trans('main.course') }}</th>
                                    <th class="text-center">{{ trans('main.status') }}</th>
                                    <th class="text-center" width="150">{{ trans('main.expire_date') }}</th>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $item)
                                        <tr>
                                            <td class="text-center">
                                                @if (!empty($item->plan))
                                                    {{ $item->plan->title }}
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->description }}</td>
                                            <td class="text-center">
                                                @if (!empty($item->content))
                                                    <a class="gray-s" href="/product/{{ $item->content->id }}">{{ $item->content->title }}</a>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($item->mode == 'publish')
                                                    <b class="blue-s">{{ trans('main.active') }}</b>
                                                @elseif($item->mode == 'pay')
                                                    <b class="green-s">{{ trans('main.paid') }}</b>
                                                @else
                                                    <b class="orange-s">{{ trans('main.waiting') }}</b>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ date('d F Y H:i',$item->created_at) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </ul>
                </li>
                <li class="">
                    <div class="link"><h2>{{ trans('main.promotion_plans') }}</h2><i class="mdi mdi-chevron-down"></i></div>
                    <ul class="submenu dblock">
                        <div class="h-10"></div>
                        <div class="row">
                            @foreach($plans as $plan)
                                <div class="col-md-3 col-xs-12 plan-box tab-con">
                                    <div class="price-section">{{ $plan->title }}</div>
                                    <div class="plan-box-section plan-box-section-s">{{ currencySign() }}{{ $plan->price }}</div>
                                    <div class="plan-box-section plan-box-section-r">{{ !empty($plan->day) ? $plan->day : '0' }} {{ trans('main.days') }}</div>
                                    <div class="plan-box-section plan-box-section-e">{{ !empty($plan->description) ? $plan->description : 'No Description' }}</div>
                                    <div class="plan-box-section"><a href="/user/video/promotion/buy/{{ $plan->id }}" class="btn btn-custom">{{ trans('main.purchase_plan') }}</a></div>
                                </div>
                            @endforeach
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
@endsection
