@extends('admin.newlayout.layout',['breadcom'=>['Report','Users']])
@section('title')
    Report
@endsection
@section('page')
    <div class="row">
        <div class="col-xs-6 col-md-3 col-sm-6 text-center">
            <section class="card bg-primary">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Total Teachers</h4>
                                <div class="info">
                                    <strong class="amount">{{ $teacherCount }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                <a href="/admin/user/vendor" class="text text-uppercase">Teacher List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xs-6 col-md-3 col-sm-6 text-center">
            <section class="card bg-info">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Total Students</h4>
                                <div class="info">
                                    <strong class="amount">{{ $studentCount }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                <a href="/admin/user/lists" class="text text-uppercase">Student List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xs-6 col-md-3 col-sm-6 text-center">
            <section class="card bg-success">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Total Admins</h4>
                                <div class="info">
                                    <strong class="amount">{{ $adminCount }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                &nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xs-6 col-md-3 col-sm-6 text-center">
            <section class="card bg-violet">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Total Users</h4>
                                <div class="info">
                                    <strong class="amount">{{ $teacherCount+$studentCount+$adminCount }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                &nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6 col-md-3 col-sm-6 text-center">
            <section class="card bg-primary">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">One to One Courses</h4>
                                <div class="info">
                                    <strong class="amount">{{ $oneCount }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                <a href="/admin/content/list" class="text text-uppercase">Course List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xs-6 col-md-3 col-sm-6 text-center">
            <section class="card bg-info">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Group Courses</h4>
                                <div class="info">
                                    <strong class="amount">{{ $groupCount }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                <a href="/admin/content/list" class="text text-uppercase">Course List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xs-6 col-md-3 col-sm-6 text-center">
            <section class="card bg-success">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Video Courses</h4>
                                <div class="info">
                                    <strong class="amount">{{ $videoCount }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                <a href="/admin/content/list" class="text text-uppercase">Course List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xs-6 col-md-3 col-sm-6 text-center">
            <section class="card bg-violet">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Total Courses</h4>
                                <div class="info">
                                    <strong class="amount">{{ $oneCount+$groupCount+$videoCount }}</strong>
                                </div>
                            </div>
                            <div class="summary-footer">
                                &nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6 col-md-3 col-sm-6 text-center">
            <section class="card bg-primary">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Total Deposit</h4>
                                <div class="info">
                                    <strong class="amount">{{ currency($allDeposit) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xs-6 col-md-3 col-sm-6 text-center">
            <section class="card bg-info">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Total Sales</h4>
                                <div class="info">
                                    <strong class="amount">{{ currency($totalSales) }}</strong>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xs-6 col-md-3 col-sm-6 text-center">
            <section class="card bg-success">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">Teacher Income</h4>
                                <div class="info">
                                    <strong class="amount">{{ currency($teacherIncome) }}</strong>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-xs-6 col-md-3 col-sm-6 text-center">
            <section class="card bg-violet">
                <div class="card-body">
                    <div class="widget-summary">
                        <div class="widget-summary-col">
                            <div class="summary">
                                <h4 class="title">MyMoon Income</h4>
                                <div class="info">
                                    <strong class="amount">{{ currency($mymoonIncome) }}</strong>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('script')
@endsection
