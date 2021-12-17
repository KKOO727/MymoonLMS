@extends('admin.newlayout.layout',['breadcom'=>['Report','Courses']])
@section('title')
    {{  trans('admin.courses_reportpage_title') }}
@endsection
@section('page')
    <div class="row">
        <div class="col-md-12 col-lg-6 col-xl-6">
            <section class="card text-center bg-danger">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">{{  trans('admin.total_courses') }}</h4>
                                <div class="info">
                                    <strong class="amount">{{ $contentCount }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                <a href="/admin/content/list" class="text text-uppercase">{{  trans('admin.list_courses') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-12 col-lg-6 col-xl-6">
            <section class="card text-center bg-success">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">{{  trans('admin.total_parts') }}</h4>
                                <div class="info">
                                    <strong class="amount">{{ $videoCount }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                <a class="text text-uppercase">{{  trans('admin.parts_deff') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <section class="card">
        <div class="card-body">
            <canvas id="myChart" width="400" height="200"></canvas>
        </div>
    </section>
@endsection
@section('script')
    <script src="/assets/vendor/chartjs/Chart.min.js"></script>
    <script>
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    @for($i=1;$i<get_option('chart_day_count',10)+1;$i++)
                        "{!! date("m/d",strtotime('-'.$i.' day')+12600) !!}",
                    @endfor
                ],
                datasets: [{
                    label: 'Courses',
                    data: [
                        @for($i=1;$i<get_option('chart_day_count',10)+1;$i++)
                        {!! groupDay($dayRegister,$i) !!},
                        @endfor
                    ],
                    backgroundColor: [
                        'rgba(255,102,51, 0.2)',
                    ],
                    borderColor: [
                        'rgba(245,184,0, 1)',
                    ],
                    borderWidth: 1
                },
                    {
                        label: 'Parts',
                        data: [
                            @for($i=1;$i<get_option('chart_day_count',10)+1;$i++)
                            {!! groupDay($videoRegister,$i) !!},
                            @endfor
                        ],
                        backgroundColor: [
                            'rgba(61,0,245, 0.2)',
                        ],
                        borderColor: [
                            'rgba(0,184,245, 1)',
                        ],
                        borderWidth: 1
                    }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
@endsection
