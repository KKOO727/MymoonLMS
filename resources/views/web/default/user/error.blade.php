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
                </div>
            </div>
        </div>
    </div>
    <div class="h-30"></div>
@endsection

