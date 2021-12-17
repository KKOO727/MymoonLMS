@include(getTemplate().'.view.layout.header')

<div id="maincontent" style="@if(!Request::is('/'))padding-top:60px;@endif">
@yield('page')

@include(getTemplate().'.view.layout.footer')
