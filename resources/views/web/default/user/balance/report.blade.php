<h3 class="paz no-margin violet"><i class="fas fa-files-o"></i> {{ trans('Transaction Report') }}</h3>
<hr>

<div class="h-20"></div>
    <div class="">
        <form class="form-horizontal">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="control-label col-md-1 tab-con">{{ trans('main.start_date') }}</label>
                <div class="col-md-3 tab-con">
                    <div class="input-group">
                        <input type="date" name="first_date" class="form-control text-center" id="first_date" value="{{ $first_date }}" required>
                        <span class="input-group-addon first_date_btn" id="first_date_btn"><span class="formicon mdi mdi-calendar-month"></span></span>
                    </div>
                </div>
                <label class="control-label col-md-1 tab-con">{{ trans('main.end_date') }}</label>
                <div class="col-md-3 tab-con">
                    <div class="input-group">
                        <input type="date" name="last_date" id="last_date" value="{!! $last_date !!}" class="form-control text-center" required>
                        <span class="input-group-addon last_date_btn" id="last_date_btn"><span class="formicon mdi mdi-calendar-month"></span></span>
                    </div>
                </div>
                <label class="control-label col-md-1 tab-con"></label>
                <div class="col-md-3 tab-con">
                    <button type="submit" class="btn btn-custom pull-left btn-100-p">{{ trans('main.show_results') }}</button>
                </div>
            </div>
        </form>
        <div class="h-20"></div>
        <div class="alert alert-success">
            <span>{{ trans('main.From') }}</span>
            <strong>{{ $first_date }}</strong>
            <span>{{ trans('main.until') }}</span>
            <strong>{{ $last_date }}</strong>
            <span>{{ trans('main.total_sold') }}</span>
            <strong>{{ $sellcount }}</strong>
            <span>{{ trans('main.and_total_amount') }}</span>
            <strong>{{ currency($prices) }}</strong>
            <span>{{ trans('main.and_your_income') }}</span>
            <strong>{{ currency($income) }}</strong>
        </div>
        <div class="h-20"></div>
        <div class="report-chart">
            <canvas id="myChart"></canvas>
        </div>
    </div>
    <div class="h-20"></div>
    <div class="h-20"></div>
@section('script')
    <script>$('#balance-hover').addClass('item-box-active');</script>
    <script type="text/javascript">
        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',

            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [
                    {
                        label: "Sales",
                        backgroundColor: '#e91e63',
                        data: [{!! implode(',',$chart['sell']) !!}],
                    },
                    {
                        label: "Income",
                        backgroundColor: '#03a9f4',
                        data: [{!! implode(',',$chart['income']) !!}],
                    }
                ]
            },

            options: {}
        });
    </script>
@endsection
