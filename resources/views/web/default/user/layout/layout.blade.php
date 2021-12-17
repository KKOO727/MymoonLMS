@include(getTemplate().'.view.layout.header',['title'=>'User Panel'])
@if($user['type'] == 'Teacher')
    @include(getTemplate().'.user.layout.menu')
@else
    @include(getTemplate().'.user.layout_user.menu')
@endif
<div id="maincontent" style="@if(!Request::is('/'))padding-top:125px;@endif">
@yield('pages')
@include(getTemplate().'.user.layout.modals')
@include(getTemplate().'.view.layout.footer')
