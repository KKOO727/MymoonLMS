@if(isset($user))
    <nav class="navbar navbar-inverse navbar-fixed-top" id="secondary-top" style="top:59px">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#second" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="second">
                <ul class="nav navbar-nav navbar-left">
                    <li class="no-child"><a href="{{URL::to('/user/dashboard')}}">Dashboard</a></li>
                    <li class="no-child"><a href="{{URL::to('/user/profile')}}">Profile </a></li>
                    <li class="no-child"><a href="{{URL::to('/user/video/buy')}}">Courses</a></li>
                    
                    @if($alert['homework'])
                        <li class="no-child"><a href="{{URL::to('/user/homework')}}">Homework</a></li>
                    @endif

                    <li class="no-child"><a href="{{URL::to('/user/messages')}}">Messages @if($alert['message'] > 0)<span class="badge badge-danger">{{$alert['message']}}</span> @endif</a></li>
                    <li class="no-child"><a href="{{URL::to('/user/balance?tab=log')}}">Balance</a></li>
                    <li class="no-child"><a href="{{URL::to('/user/ticket')}}">Support @if($alert['tickets'] > 0)<span class="badge badge-danger">{{$alert['tickets']}}</span> @endif </a></li>
                    <li class="no-child"><a href="{{URL::to('/user/ticket/notification')}}">Notifications @if($alert['notification'] > 0)<span class="badge badge-danger">{{$alert['notification']}}</span> @endif</a></li>
                </ul>
            </div>
        </div>
    </nav>
@endif
