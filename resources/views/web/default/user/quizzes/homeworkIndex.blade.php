@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <div class="h-30"></div>

    <div class="container-fluid">
        <div class="row product-body">
            <div class="container">
                <div class="row">

                    <div class="col-md-4">
                        <h3 class="violet"><i class="fas fa-book"></i> Course List</h3>
                        <div class="h-30"></div>
                        <ul class="nav nav-tabs homework-tab" role="tablist">
                            <?php $i=0; ?>
                            @foreach($active_courses as $sell)
                                    <?php
                                    $class="";
                                    if($i==0){
                                        $class=" active";
                                    } ?>

                                <li role="presentation" class="{{$class}}"><a href="#tab{{$sell->id}}" aria-controls="tab{{$sell->id}}" role="tab" data-toggle="tab">{{get_courseName($sell->content_id,false)}}</a></li>
                            <?php $i++; ?>
                                @endforeach
                        </ul>
                    </div>
                    <div class="col-md-8">
                        <h3 class="violet"><i class="fas fa-book-open"></i> Class List</h3>
                        <div class="h-30"></div>
                        <div class="tab-content">
                            <?php $i=0; ?>
                            @foreach($active_courses as $sell)
                                <?php
                                $class="";
                                if($i==0){
                                    $class=" active";
                                } ?>
                                <div role="tabpanel" class="tab-pane{{$class}}" id="tab{{$sell->id}}">
                                    <table class="table table-bordered table-striped violet-table">
                                        <thead>
                                        <tr>
                                            <th>Day</th>
                                            <th>Class Start Time</th>
                                            <th>Class End Time</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                                foreach($schedule[$sell->content_id] as $event) {

                                                    $only_event_date = substr($event->start, 0, 10);
                                                    $startHour = substr($event->start, -9, -4);
                                                    $endHour = substr($event->end, -9, -4);

                                                    $start = $only_event_date . ' ' . $startHour;
                                                    $end = $only_event_date . ' ' . $endHour;

                                                ?>
                                                <tr>
                                                    <td>{{$only_event_date}}</td>
                                                    <td>{{date("g:i A", strtotime($start))}}</td>
                                                    <td>{{date("g:i A", strtotime($end))}}</td>
                                                    <td><a href="/user/homework/class?id={{$event->id}}" class="btn btn-primary">Homework</a></td>
                                                </tr>

                                            <?php } ?>
                                        </tbody>
                                    </table>

                                </div>
                                    <?php $i++; ?>
                            @endforeach
                        </div>


                    </div>
                </div>


                <div class="h-30"></div>
            </div>
        </div>
    </div>

    <div class="h-30"></div>
@endsection
@section('script')

@endsection

