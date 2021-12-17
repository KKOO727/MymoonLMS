@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{'Something went wrong'}}
@endsection
@section('page')
    <?php
    $base_url = baseURL();
    ?>

    <div class="h-80"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="container">
                <div class="col-md-12" style="min-height:160px">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($error as $err)
                                <li>{{$err}}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="row text-center" style="padding-top: 5%;">
                        <a href="{{URL::to('/user/profile?tab=personal')}}" class="btn btn-danger btn-sm">Go To Personal Details Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="h-30"></div>
@endsection

