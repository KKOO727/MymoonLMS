@section('pageheader')

    <header class="page-header" style="top:0px !important;">
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="#">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>{{  trans('admin.admin_panel') }}</span></li>
                @if(!empty($breadcom))
                    @foreach($breadcom as $bread)
                        <li><span>{{$bread}}</span></li>
                    @endforeach
                @endif
            </ol>
        </div>

        <div class="header-left">

            <ul class="notifications">
                <li>
                    <a href="/admin/profile" class="dropdown-toggle notification-icon">
                        <i class="fa fa-user"></i>
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                        <i class="fa fa-envelope"></i>
                        <span class="badge">{{ count($alert['ticket']) }}</span>
                    </a>

                    <div class="dropdown-menu notification-menu">
                        <div class="notification-title">
                            <span class="pull-left label label-default">{{ count($alert['ticket']) }}</span>
                            {{  trans('admin.header_tickets') }}
                        </div>

                        <div class="content">
                            <ul>
                                @foreach($alert['ticket'] as $ticky)
                                    <li>
                                        <a href="/admin/ticket/reply/{{ $ticky->id }}" class="clearfix">
                                            <span class="title">{{ $ticky->user->name }}</span>
                                            <span class="message">{{ $ticky->title }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <hr/>

                            <div class="text-right">
                                <a href="/admin/ticket/tickets" class="view-more">{{  trans('admin.view_all') }}</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
                        <i class="fa fa-bell"></i>
                        <span class="badge">{{ count($alert['notification']) }}</span>
                    </a>

                    <div class="dropdown-menu notification-menu">
                        <div class="notification-title">
                            <span class="pull-left label label-default">{{ count($alert['notification']) }}</span>
                            {{  trans('admin.header_notifications') }}
                        </div>

                        <div class="content">
                            <ul>
                                @foreach($alert['notification'] as $notify)
                                    <li>
                                        <a href="/admin/notification/edit/{{ $notify->id }}" class="clearfix">
                                            <span class="title">{{ !empty($notify->user) ? $notify->user->name : '' }}</span>
                                            <span class="message">{{ $notify->title }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <hr/>

                            <div class="text-right">
                                <a href="/admin/notification/list" class="view-more">{{  trans('admin.view_all') }}</a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

        </div>
    </header>

@endsection
