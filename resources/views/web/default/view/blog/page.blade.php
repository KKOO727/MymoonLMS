@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
@endsection
@section('page')
    <div class="container-fluid">
        <div class="h-20"></div>
        <div class="container">
            <div class="light-box">

                <div class="row">
                    <div class="col-xs-12">
                        {!! $content !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="h-20"></div>
    </div>
@endsection
