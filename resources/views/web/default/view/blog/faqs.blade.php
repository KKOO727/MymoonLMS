@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
@endsection
@section('page')
    <div class="h-20"></div>
    <div class="container">
        <div class="light-box">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="text-center violet bold">FAQs</h1>
                    <div class="h-20"></div>
                    <h4 class="violet bold">For students:</h4>
                    <div class="panel-group" id="accordion">

                        @foreach($student_faqs as $faq)
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$faq->id}}">
                                            <span class="glyphicon glyphicon-plus"></span> {{$faq->question}}
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse{{$faq->id}}" class="panel-collapse collapse @if($faq->id == 1){{'in'}}@endif">
                                    <div class="panel-body">
                                        {{$faq->answer}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <hr>
                    <h4 class="violet bold">For teachers:</h4>
                    <hr>
                    <div class="panel-group" id="accordion2">

                        @foreach($teacher_faqs as $faq)
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion2" href="#collapse{{$faq->id}}">
                                            <span class="glyphicon glyphicon-plus"></span> {{$faq->question}}
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse{{$faq->id}}" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        {{$faq->answer}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        <div class="h-20"></div>
    </div>
@endsection
@section('script')
    <style>
        .glyphicon{color:#932680;}
    </style>
    <script>
        $(document).ready(function(){
            $('.collapse.in').each(function(){
                $(this).parent().find(".glyphicon").removeClass("glyphicon-plus").addClass("glyphicon-minus");
            });

            $('.collapse').on('shown.bs.collapse', function(){
                $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
            }).on('hidden.bs.collapse', function(){
                $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
            });
        });
    </script>
    @endsection
