@extends('admin.newlayout.layout',['breadcom'=>['Reports','Financial']])
@section('title')
    {{  trans('admin.financial_reports_page_title') }}
@endsection
@section('page')
    <div class="row">
        <div class="col-md-3">
            <section class="card text-center bg-primary">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">{{  trans('Total Deposit') }}</h4>
                                <div class="info">
                                    <strong class="amount">{!! currencySign('EUR') !!}{{ number_format($allDeposit,2) }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-3">
            <section class="card text-center bg-info">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">{{  trans('Total Sales') }}</h4>
                                <div class="info">
                                    <strong class="amount">{!! currencySign('EUR') !!}{{ number_format($allIncome,2) }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


        <div class="col-md-3">
            <section class="card text-center bg-primary">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">{{  trans('admin.vendors_income') }}</h4>
                                <div class="info">
                                    <strong class="amount">{!! currencySign('EUR') !!}{{ number_format($userIncome,2) }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-3">
            <section class="card text-center bg-warning">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">{{  trans('admin.business_income') }}</h4>
                                <div class="info">
                                    <strong class="amount">{!! currencySign('EUR') !!}{{ number_format($siteIncome,2) }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="col-md-12 col-lg-4 col-xl-4">
            <section class="card text-center bg-danger">
                <div class="card-body">
                    <div class="widget-summary">

                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">{{  trans('admin.total_sales') }}</h4>
                                <div class="info">
                                    <strong class="amount">{{ $sellCount }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                <a href="/admin/buysell/list" class="text text-uppercase">{{  trans('admin.sales_list') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-12 col-lg-4 col-xl-4">
            <section class="card text-center bg-success">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">{{  trans('admin.total_transactions') }}</h4>
                                <div class="info">
                                    <strong class="amount">{{ $transactionCount }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                <a href="/admin/balance/transaction" class="text text-uppercase">{{  trans('admin.transactions_list') }}</a>
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
                    label: 'Sales',
                    data: [
                        @for($i=1;$i<get_option('chart_day_count',10)+1;$i++)
                        {!! groupDay($dayRegister,$i) !!},
                        @endfor
                    ],
                    backgroundColor: [
                        'rgba(2,22,222, 0.2)',
                    ],
                    borderColor: [
                        'rgba(1,11,111, 1)',
                    ],
                    borderWidth: 1
                }, {
                    label: 'Transactions',
                    data: [
                        @for($i=1;$i<get_option('chart_day_count',10)+1;$i++)
                        {!! groupDay($transactionRegister,$i) !!},
                        @endfor
                    ],
                    backgroundColor: [
                        'rgba(51,255,204, 0.2)',
                    ],
                    borderColor: [
                        'rgba(204,255,51, 1)',
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
