@include(getTemplate().'.view.layout.header',['title'=>'User Panel'])
@include(getTemplate().'.user.layout.menu')
@section('title','Courses')
<div class="h-20"></div>
<div class="container-fluid">

</div>

@section('script')
    <script>$('#buy-hover').addClass('item-box-active');</script>
@endsection

@include(getTemplate().'.user.layout.modals')
@include(getTemplate().'.view.layout.footer')
