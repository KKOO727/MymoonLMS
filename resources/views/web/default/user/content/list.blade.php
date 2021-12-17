@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <?php
    if (!isset($_GET['tab'])) {
        $tab = 'courses';
    } else {
        $tab = $_GET['tab'];
    }
    ?>
    <div class="h-20"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="leftMenu">
                    <ul>
                        <li><a href="{{URL::to('/user/content?tab=courses')}}"><i class="fas fa-book"></i> My Courses</a></li>
                        <li><a href="{{URL::to('/user/content/edit/onetoone')}}"><i class="fas fa-user-friends"></i> One to One Configuration</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#newCourseModal"><i class="fas fa-book-open"></i> New Course</a></li>

                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="light-box">
                    @if($tab == 'courses')
                        @include(getTemplate().'/user/content/courseList')
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="h-10"></div>
@endsection
@section('script')
@endsection
