@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <?php
    if (!isset($_GET['tab'])) {
        $tab = 'report';
    } else {
        $tab = $_GET['tab'];
    }

    ?>
    <div class="h-20"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="leftMenu">
                    <ul>
                        @if($user->type == 'Teacher')
                        <li><a href="{{URL::to('/user/balance?tab=sell')}}"><i class="fas fa-files-o"></i> Sold Courses</a></li>
                        <li><a href="{{URL::to('/user/balance?tab=payout')}}"><i class="fas fa-bank"></i> Payout</a></li>
                        @else
                            <li><a data-toggle="modal" href="#topUpModal"><i class="fas fa-money-bill-wave"></i> Top Up</a></li>
                        @endif
                        <li><a href="{{URL::to('/user/balance?tab=log')}}"><i class="fas fa-list"></i> Balance Log</a></li>



                    </ul>
                </div>
                <div class="h-20"></div>
                <div class="h-20"></div>
                <div class="ucp-section-box">
                    <div class="header paz"></div>
                    <div class="body">
                        <h4>{{ trans('MyMoon Balance') }}</h4>
                        <h4 class="bold">{{currency(getUserbalance($user['id']))}}</h4>
                        <!--<a data-toggle="modal" href="#topUpModal" class="btn btn-primary">Add Balance</a>
                        <a href="/user/balance/log" class="btn btn-primary">Credit Dashboard</a>-->
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="light-box">
                    @if($tab == 'sell')
                        @include(getTemplate().'/user/balance/download')
                    @elseif($tab == 'log')
                        @include(getTemplate().'/user/balance/log')
                    @elseif($tab == 'report')
                        @include(getTemplate().'/user/balance/report')
                    @elseif($tab == 'payout')
                        @include(getTemplate().'/user/balance/payout')
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="h-10"></div>
@endsection

