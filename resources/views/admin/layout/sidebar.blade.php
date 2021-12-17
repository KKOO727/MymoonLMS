@section('sidebar')

    <aside id="sidebar-left" class="sidebar-left" style="top:0px !important;">

        <div class="sidebar-header">
            <div class="sidebar-title">
                {{  trans('admin.admin_panel') }}
            </div>
            <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
                <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
            </div>
        </div>
        <div class="nano">
            <div class="nano-content">
                <nav id="menu" class="nav-main" role="navigation">
                    <ul class="nav nav-main">
                        @if(checkAccess('report'))
                            <li class="nav-parent {!! expandSidebarMenu('report',$capatibility) !!}" id="report">
                                <a>
                                    <i class="fa fa-home" aria-hidden="true"></i>
                                    <span>{{  trans('admin.dashboard') }}</span>
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('report','user') !!}>
                                        <a href="/admin/report/user">
                                            <span>{{  trans('admin.users_report') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('report','content') !!}>
                                        <a href="/admin/report/content">
                                            <span>{{  trans('admin.products_report') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('report','balance') !!}>
                                        <a href="/admin/report/balance">
                                            <span>{{  trans('admin.financial_report') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>@endif
                        @if(checkAccess('user'))
                            <li class="nav-parent {!! expandSidebarMenu('user',$capatibility) !!}" id="user">
                                <a>
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <span>{{  trans('admin.users') }}</span>
                                    @if(isset($alert['seller_apply']) && $alert['seller_apply'] > 0)
                                        <span class="pull-left label label-primary">{{ $alert['seller_apply'] ?? 0 }}</span>
                                    @endif
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('user','lists') !!}>
                                        <a href="/admin/user/lists">
                                            <span>{{  trans('admin.list') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('user','category') !!}>
                                        <a href="/admin/user/category">
                                            <span>{{  trans('admin.user_groups') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('user','rate') !!}>
                                        <a href="/admin/user/rate">
                                            <span>{{  trans('admin.users_badges') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('user','seller') !!}>
                                        <a href="/admin/user/seller">
                                            <span>{{  trans('admin.identity_verification') }}</span>
                                            @if(isset($alert['seller_apply']) and $alert['seller_apply'] > 0)
                                                <span class="pull-left label label-primary">{{ $alert['seller_apply'] ?? 0 }}</span>
                                            @endif
                                        </a>
                                    </li>
                                </ul>
                            </li>@endif
                        @if(checkAccess('channel'))
                            <li class="nav-parent {!! expandSidebarMenu('channel',$capatibility) !!}" id="chanel">
                                <a>
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    <span>{{  trans('admin.channels') }}</span>
                                    @if($alert['channel_request']>0)
                                        <span class="pull-left label label-primary">{{ $alert['channel_request'] ?? 0 }}</span>
                                    @endif
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('channel','list') !!}>
                                        <a href="/admin/channel/list">
                                            <span>{{  trans('admin.list') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('channel','request') !!}>
                                        <a href="/admin/channel/request">
                                            <span>{{  trans('admin.verification_requests') }}</span>
                                            @if($alert['channel_request']>0)
                                                <span class="pull-left label label-primary">{{ $alert['channel_request'] ?? 0 }}</span>
                                            @endif
                                        </a>
                                    </li>
                                </ul>
                            </li>@endif
                        @if(checkAccess('content'))
                            <li class="nav-parent {!! expandSidebarMenu('content',$capatibility) !!}" id="content">
                                <a>
                                    <i class="fa fa-video-camera" aria-hidden="true"></i>
                                    <span>{{  trans('admin.courses') }}</span>
                                    @if(isset($alert['content_waiting']) and $alert['content_waiting'] > 0)
                                        <span class="pull-left label label-primary">{{ $alert['content_waiting'] ?? 0 }}</span>
                                    @endif
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('content','list') !!}>
                                        <a href="/admin/content/list">
                                            <span>{{  trans('admin.list') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('content','waiting') !!}>
                                        <a href="/admin/content/waiting">
                                            <span>{{  trans('admin.pending_courses') }}</span>
                                            @if(isset($alert['content_waiting']) and $alert['content_waiting'] > 0)
                                                <span class="pull-left label label-primary">{{ $alert['content_waiting'] ?? 0 }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('content','draft') !!}>
                                        <a href="/admin/content/draft">
                                            <span>{{  trans('admin.unpublished_courses') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('content','comment') !!}>
                                        <a href="/admin/content/comment">
                                            <span>{{  trans('admin.corse_comments') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('content','support') !!}>
                                        <a href="/admin/content/support">
                                            <span>{{  trans('admin.support_tickets') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('content','category') !!}>
                                        <a href="/admin/content/category">
                                            <span>{{  trans('admin.categories') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        <li class="nav-parent {!! expandSidebarMenu('live',$capatibility) !!}" id="live">
                            <a>
                                <i class="fa fa-video-camera" aria-hidden="true"></i>
                                <span>Live Meeting</span>
                                @if(isset($alert['content_waiting']) and $alert['content_waiting'] > 0)
                                    <span class="pull-left label label-primary">{{ $alert['content_waiting'] ?? 0 }}</span>
                                @endif
                            </a>
                        </li>
                        @if(checkAccess('balance'))
                            <li class="nav-parent {!! expandSidebarMenu('balance',$capatibility) !!}" id="balance">
                                <a>
                                    <i class="fa fa-line-chart" aria-hidden="true"></i>
                                    <span>{{  trans('admin.financial') }}</span>
                                    @if(isset($alert['withdraw']) and $alert['withdraw'] > 0)
                                        <span class="pull-left label label-primary">{{ $alert['withdraw'] ?? 0 }}</span>
                                    @endif
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('balance','list') !!}>
                                        <a href="/admin/balance/list">
                                            <span>{{  trans('admin.financial_documents') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('balance','withdraw') !!}>
                                        <a href="/admin/balance/withdraw">
                                            <span>{{  trans('admin.withdrawal_list') }}</span>
                                            @if(isset($alert['withdraw']) and $alert['withdraw'] > 0)
                                                <span class="pull-left label label-primary">{{ $alert['withdraw'] ?? 0 }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('balance','new') !!}>
                                        <a href="/admin/balance/new">
                                            <span>{{  trans('admin.new_balance') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('balance','analyzer') !!}>
                                        <a href="/admin/balance/analyzer">
                                            <span>{{  trans('admin.financial_analyser') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('report','transaction') !!}>
                                        <a href="/admin/balance/transaction">
                                            <span>{{  trans('admin.transactions_report') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if(checkAccess('buysell'))
                            <li class="nav-parent {!! expandSidebarMenu('buysell',$capatibility) !!}" id="balance">
                                <a>
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    <span>{{  trans('admin.sales') }}</span>
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('buysell','list') !!}>
                                        <a href="/admin/buysell/list">
                                            <span>{{  trans('admin.sales_list') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>@endif
                        @if(checkAccess('manager'))
                            <li class="nav-parent {!! expandSidebarMenu('manager',$capatibility) !!}" id="manager">
                                <a>
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                    <span>{{  trans('admin.employees') }}</span>
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('manager','lists') !!}>
                                        <a href="/admin/manager/lists">
                                            <span>{{  trans('admin.list') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('manager','new') !!}>
                                        <a href="/admin/manager/new">
                                            <span>{{  trans('admin.new_employee') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>@endif
                        @if(checkAccess('request'))
                            <li class="nav-parent {!! expandSidebarMenu('request',$capatibility) !!}" id="request">
                                <a>
                                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                                    <span>{{  trans('admin.course_requests') }}</span>
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('request','list') !!}>
                                        <a href="/admin/request/list">
                                            <span>{{  trans('admin.requests_list') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('record','list') !!}>
                                        <a href="/admin/request/record/list">
                                            <span>{{  trans('admin.future_courses') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>@endif
                        @if(checkAccess('ads'))
                            <li class="nav-parent {!! expandSidebarMenu('ads',$capatibility) !!}" id="manager">
                                <a>
                                    <i class="fa fa-buysellads" aria-hidden="true"></i>
                                    <span>{{  trans('admin.advertising') }}</span>
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('ads','plans') !!}>
                                        <a href="/admin/ads/plans">
                                            <span>{{  trans('admin.plans') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('ads','newplan') !!}>
                                        <a href="/admin/ads/newplan">
                                            <span>{{  trans('admin.new_plan') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('ads','request') !!}>
                                        <a href="/admin/ads/request">
                                            <span>{{  trans('admin.advertisement_requests') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('ads','box') !!}>
                                        <a href="/admin/ads/box">
                                            <span>{{  trans('admin.banners') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('ads','newbox') !!}>
                                        <a href="/admin/ads/newbox">
                                            <span>{{  trans('admin.new_banner') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('ads','vip') !!}>
                                        <a href="/admin/ads/vip">
                                            <span>{{  trans('admin.featured_products') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>@endif
                        @if(checkAccess('blog'))
                            <li class="nav-parent {!! expandSidebarMenu('blog',$capatibility) !!}" id="blog">
                                <a>
                                    <i class="fa fa-wordpress" aria-hidden="true"></i>
                                    <span>{{  trans('admin.blog_articles') }}</span>
                                    @if($alert['article_request'] > 0)
                                        <span class="pull-left label label-primary">{{ $alert['article_request'] ?? 0 }}</span>
                                    @endif
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('blog','posts') !!}>
                                        <a href="/admin/blog/posts">
                                            <span>{{  trans('admin.blog_posts') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('blog','post') !!}>
                                        <a href="/admin/blog/post/new">
                                            <span>{{  trans('admin.new_post') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('blog','category') !!}>
                                        <a href="/admin/blog/category">
                                            <span>{{  trans('admin.contents_categories') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('blog','comments') !!}>
                                        <a href="/admin/blog/comments">
                                            <span>{{  trans('admin.blog_comments') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('blog','article') !!}>
                                        <a href="/admin/blog/article">
                                            <span>{{  trans('admin.articles') }}</span>
                                            @if($alert['article_request'] > 0)
                                                <span class="pull-left label label-primary">{{ $alert['article_request'] ?? 0 }}</span>
                                            @endif
                                        </a>
                                    </li>
                                </ul>
                            </li>@endif
                        @if(checkAccess('ticket'))
                            <li class="nav-parent {!! expandSidebarMenu('ticket',$capatibility) !!}" id="ticket">
                                <a>
                                    <i class="fa fa-life-ring" aria-hidden="true"></i>
                                    <span>{{  trans('admin.support') }}</span>
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('ticket','tickets') !!}>
                                        <a href="/admin/ticket/tickets">
                                            <span>{{  trans('admin.list') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('ticket','ticketsopen') !!}>
                                        <a href="/admin/ticket/ticketsopen">
                                            <span>{{  trans('admin.pending_tickets') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('ticket','ticketsclose') !!}>
                                        <a href="/admin/ticket/ticketsclose">
                                            <span>{{  trans('admin.closed_tickets') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('ticket','category') !!}>
                                        <a href="/admin/ticket/category">
                                            <span></span>{{  trans('admin.support_departments') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('ticket','new') !!}>
                                        <a href="/admin/ticket/new">
                                            <span>{{  trans('admin.submit_ticket') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>@endif
                        @if(checkAccess('notification'))
                            <li class="nav-parent {!! expandSidebarMenu('notification',$capatibility) !!}" id="notification">
                                <a>
                                    <i class="fa fa-bell" aria-hidden="true"></i>
                                    <span>{{  trans('admin.notifications') }}</span>
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('notification','') !!}>
                                        <a href="/admin/notification/template">
                                            <span>{{  trans('admin.templates') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('notification','tnew') !!}>
                                        <a href="/admin/notification/template/tnew">
                                            <span>{{  trans('admin.new_template') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('notification','list') !!}>
                                        <a href="/admin/notification/list">
                                            <span></span>{{  trans('admin.sent_notifications') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('notification','new') !!}>
                                        <a href="/admin/notification/new">
                                            <span>{{  trans('admin.new_notification') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>@endif
                        @if(checkAccess('email'))
                            <li class="nav-parent {!! expandSidebarMenu('email',$capatibility) !!}" id="email">
                                <a>
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    <span></span>{{  trans('admin.emails') }}</span>
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('email','templates') !!}>
                                        <a href="/admin/email/templates">
                                            <span>{{  trans('admin.email_templates') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('email','template') !!}>
                                        <a href="/admin/email/template/new">
                                            <span>{{  trans('admin.new_template') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('email','new') !!}>
                                        <a href="/admin/email/new">
                                            <span>{{  trans('admin.send_email') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>@endif
                        @if(checkAccess('discount'))
                            <li class="nav-parent {!! expandSidebarMenu('discount',$capatibility) !!}" id="discount">
                                <a>
                                    <i class="fa fa-gift" aria-hidden="true"></i>
                                    <span>{{  trans('admin.promotions_discounts') }}</span>
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('discount','list') !!}>
                                        <a href="/admin/discount/list">
                                            <span>{{  trans('admin.giftcards_list') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('discount','new') !!}>
                                        <a href="/admin/discount/new">
                                            <span></span>{{  trans('admin.new_giftcard') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('discount','contentlist') !!}>
                                        <a href="/admin/discount/contentlist">
                                            <span>{{  trans('admin.promotions_list') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('discount','contentnew') !!}>
                                        <a href="/admin/discount/contentnew">
                                            <span>{{  trans('admin.new_promotion') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>@endif
                        @if(checkAccess('setting'))
                            <li class="nav-parent {!! expandSidebarMenu('setting',$capatibility) !!}" id="setting">
                                <a href="javascript:void(0);">
                                    <i class="fa fa-diamond" aria-hidden="true"></i>
                                    <span>{{  trans('admin.settings') }}</span>
                                </a>
                                <ul class="nav nav-children">
                                    <li {!! activeSidebarSubmenu('setting','main') !!}>
                                        <a href="/admin/setting/main">
                                            <span>{{  trans('admin.general_settings') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('setting','display') !!}>
                                        <a href="/admin/setting/display">
                                            <span>{{  trans('admin.custom_codes') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('setting','user') !!}>
                                        <a href="/admin/setting/user">
                                            <span>{{  trans('admin.users_settings') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('setting','content') !!}>
                                        <a href="/admin/setting/content">
                                            <span>{{  trans('admin.course_settings') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('setting','term') !!}>
                                        <a href="/admin/setting/term">
                                            <span>{{  trans('admin.rules') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('setting','blog') !!}>
                                        <a href="/admin/setting/blog">
                                            <span>{{  trans('admin.blog_article_settings') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('setting','notification') !!}>
                                        <a href="/admin/setting/notification">
                                            <span>{{  trans('admin.notification_settings') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('setting','social') !!}>
                                        <a href="/admin/setting/social">
                                            <span>{{  trans('admin.social_networks') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('setting','footer') !!}>
                                        <a href="/admin/setting/footer">
                                            <span>{{  trans('admin.footer_settings') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('setting','pages') !!}>
                                        <a href="/admin/setting/pages">
                                            <span>{{  trans('admin.custom_pages') }}</span>
                                        </a>
                                    </li>
                                    <li {!! activeSidebarSubmenu('setting','default') !!}>
                                        <a href="/admin/setting/default">
                                            <span>{{  trans('admin.default_placeholders') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>@endif
                            @if(checkAccess('setting'))
                                <li class="nav-parent {!! expandSidebarMenu('blog',$capatibility) !!}" id="blog">
                                    <a href="javascript:void(0);">
                                        <i class="fa fa-diamond" aria-hidden="true"></i>
                                        <span>{{  trans('Blog') }}</span>
                                    </a>
                                    <ul class="nav nav-children">

                                        <li {!! activeSidebarSubmenu('blog','all') !!}>
                                            <a href="/admin/blog/posts">
                                                <span>{{  trans('All Posts') }}</span>
                                            </a>
                                        </li>
                                        <li {!! activeSidebarSubmenu('blog','new') !!}>
                                            <a href="/admin/blog/post/new">
                                                <span>{{  trans('New Post') }}</span>
                                            </a>
                                        </li>
                                        <li {!! activeSidebarSubmenu('blog','category') !!}>
                                            <a href="/admin/blog/category">
                                                <span>{{  trans('Category') }}</span>
                                            </a>
                                        </li>
                                        <li {!! activeSidebarSubmenu('blog','comments') !!}>
                                            <a href="/admin/blog/comments">
                                                <span>{{  trans('Comments') }}</span>
                                            </a>
                                        </li>

                                        <li {!! activeSidebarSubmenu('blog','article') !!}>
                                            <a href="/admin/setting/notification">
                                                <span>{{  trans('Articles') }}</span>
                                            </a>
                                        </li>
                                        <li {!! activeSidebarSubmenu('blog','settings') !!}>
                                            <a href="/admin/blog/article">
                                                <span>{{  trans('Blog Settings') }}</span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>@endif
                        <li>
                            <a href="/admin/logout">
                                <i class="fa fa-sign-out" aria-hidden="true"></i>
                                <span>{{  trans('admin.exit') }}</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

    </aside>

@endsection
