@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ get_option('site_title','') }} - {{ !empty($category->title) ? $category->title : 'Search' }}
@endsection
@section('page')
    <div class="loading" style="display:none">Loading&#8230;</div>
    <?php $base_url = baseURL(); ?>
        <div class="h-20"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <h3>Online Arabic tutors & teachers</h3>
                    <p>Choose one of our experienced English teachers and receive the best learning experience
                        online!</p>
                <?php
                if (!isset($q)) {
                    $q = '';
                } ?>
                <!--<span style="font-size:24px;line-height:46px;">Find a teacher : {{$q}}</span><br>-->
                </div>

            </div>
        </div>
        <div class="h-20"></div>
        <form action="/search" id="mainSearchForm">
            {{csrf_field()}}

            @if($agent->isMobile())
                <div class="container">
                    <div class="row d-flex">
                        <div class="col-md-6 col-xs-12 text-left">
                            <div class="form-group">
                                <select class="form-control" name="order">
                                    <option value="Ratings">Best Ratings</option>
                                    <option value="Lowest">Price: Lowest first</option>
                                    <option value="Highest">Price: Highest first</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group text-left">
                                <div class="container-2">
                                    <input type="text" id="searchCat" name="q" placeholder="{{ !empty($q) ? $q : 'Search in all categories' }}"/>
                                    <span class="icon"><i class="homeicon mdi mdi-magnify"></i></span>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center">
                        <button class="btn btn-primary btn-filters-menu" style="width: 90%;" onclick="openFilter()" type="button">Filters</button>    
                    </div>

                    <div id="filters-menu">
                        <a href="javascript:void(0)" class="closebtn" onclick="closeFilter()"><i class="fa fa-times"></i></a>

                        <div class="container" style="padding-top: 15%;">
                            <div class="row">
                                <div class="search-sidebar">
                                    <div class="panel-group" id="arabic">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#arabic" href="#arabic1"
                                                       aria-expanded="true" class="">
                                                        Arabic <i class="fa fa-angle-down pull-right"></i>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="arabic1" class="panel-collapse collapse in" aria-expanded="true" style="">
                                                <div class="panel-body">
                                                    <?php
                                                    if (!isset($teaches)) {
                                                        $categories = array();
                                                    } else {
                                                        $categories = $teaches;
                                                    }
                                                    $list = contentCategoryCheckbox($categories, ['1']);
                                                    echo $list;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-group" id="second">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#second" href="#second1"
                                                       aria-expanded="true" class="collapsed">
                                                        Quran <i class="fa fa-angle-down pull-right"></i>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="second1" class="panel-collapse collapse" aria-expanded="false" style="">
                                                <div class="panel-body">
                                                    <?php
                                                    $list = contentCategoryCheckbox($categories, ['2']);
                                                    echo $list;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-group" id="dialects">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#dialects" href="#dialects1"
                                                       aria-expanded="true" class="collapsed">
                                                        Arabic Dialects <i class="fa fa-angle-down pull-right"></i>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="dialects1" class="panel-collapse collapse" aria-expanded="false" style="">
                                                <div class="panel-body">
                                                    <?php
                                                    $list = contentCategoryCheckbox($categories, ['3']);
                                                    echo $list;
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="panel-group" id="learning">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#learning" href="#learning1"
                                                       aria-expanded="true" class="collapsed">
                                                        Learning Style <i class="fa fa-angle-down pull-right"></i>
                                                    </a>
                                                </h4>
                                            </div>

                                            <?php
                                            $styles = array('1' => '1:1 Course', '2' => 'Group Course', '3' => 'Video Course');
                                            if (!isset($key_styles)) {
                                                $key_styles = array();
                                                $cls = '';
                                                $expand = 'false';
                                            } else {
                                                $cls = ' in';
                                                $expand = 'true';
                                            }
                                            ?>
                                            <div id="learning1" class="panel-collapse collapse{{$cls}}" aria-expanded="{{$expand}}" style="">
                                                <div class="panel-body">
                                                    <ul>

                                                        @foreach($styles as $key=>$item)
                                                            <li><input type="checkbox" name="styles[]" value="{{$key}}"
                                                                       class="checkmark-circled" @if(in_array($key,$key_styles)){{'checked'}}@endif> {{$item}}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="panel-group" id="language">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#language" href="#language1"
                                                       aria-expanded="true" class="collapsed">
                                                        Language <i class="fa fa-angle-down pull-right"></i>
                                                    </a>
                                                </h4>
                                            </div>
                                            <?php
                                            $languages = getLanguageList();
                                            if (!isset($language)) {
                                                $language = array();
                                                $cls = '';
                                                $expand = 'false';
                                            } else {
                                                $cls = ' in';
                                                $expand = 'true';
                                            }
                                            ?>
                                            <div id="language1" class="panel-collapse collapse{{$cls}}" aria-expanded="{{$expand}}" style="">
                                                <div class="panel-body">
                                                    <ul>
                                                        @foreach($languages as $item)
                                                            <li><input type="checkbox" name="language[]" value="{{$item}}"
                                                                       class="checkmark-circled" @if(in_array($item,$language)){{'checked'}}@endif> {{$item}}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="panel-group" id="gender">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#gender" href="#gender1"
                                                       aria-expanded="true" class="collapsed">
                                                        Gender <i class="fa fa-angle-down pull-right"></i>
                                                    </a>
                                                </h4>
                                            </div>
                                            <?php
                                            $genders = array('Male', 'Female', 'All');
                                            if (!isset($gender)) {
                                                $gender = array();
                                                $cls = '';
                                                $expand = 'false';
                                            } else {
                                                $cls = ' in';
                                                $expand = 'true';
                                            }
                                            ?>
                                            <div id="gender1" class="panel-collapse collapse{{$cls}}" aria-expanded="{{$expand}}" style="">
                                                <div class="panel-body">
                                                    <ul>

                                                        @foreach($genders as $item)
                                                            <li><input type="checkbox" name="gender[]" value="{{$item}}"
                                                                       class="checkmark-circled" @if(in_array($item,$gender)){{'checked'}}@endif> {{$item}}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-group" id="country">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#country" href="#country1"
                                                       aria-expanded="true" class="collapsed">
                                                        From Country <i class="fa fa-angle-down pull-right"></i>
                                                    </a>
                                                </h4>
                                            </div>
                                            <?php
                                            $countrys = getCountryList();
                                            if (!isset($country)) {
                                                $country = array();
                                                $cls = '';
                                                $expand = 'false';
                                            } else {
                                                $cls = ' in';
                                                $expand = 'true';
                                            }
                                            ?>
                                            <div id="country1" class="panel-collapse collapse{{$cls}}" aria-expanded="{{$expand}}" style="">
                                                <div class="panel-body">
                                                    <ul>
                                                        @foreach($countrys as $item)
                                                            <li><input type="checkbox" name="country[]" value="{{$item->country_code}}"
                                                                       class="checkmark-circled" @if(in_array($item->country_code,$country)){{'checked'}}@endif> {{$item->country_name}}
                                                            </li>
                                                        @endforeach
                                                        <li><input type="checkbox" name="country[]" value=""
                                                                   class="checkmark-circled"> All
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel-group" id="teaches">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#teaches" href="#teaches1"
                                                       aria-expanded="true" class="collapsed">
                                                        Teaches <i class="fa fa-angle-down pull-right"></i>
                                                    </a>
                                                </h4>
                                            </div>
                                            <?php
                                            $student_types = array('Adults', 'Kids', 'All');
                                            if (!isset($student_type)) {
                                                $student_type = array();
                                                $cls = '';
                                                $expand = 'false';
                                            } else {
                                                $cls = ' in';
                                                $expand = 'true';
                                            }
                                            ?>
                                            <div id="teaches1" class="panel-collapse collapse{{$cls}}" aria-expanded="{{$expand}}" style="">
                                                <div class="panel-body">
                                                    <ul>

                                                        @foreach($student_types as $item)
                                                            <li><input type="checkbox" name="student_type[]" value="{{$item}}"
                                                                       class="checkmark-circled" @if(in_array($item,$student_type)){{'checked'}}@endif> {{$item}}
                                                            </li>
                                                        @endforeach

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center" id="resetFilter" style="padding-left: 5%; padding-right: 5%; width: 50%;">
                                    <div class="h-20"></div>
                                    <a style="padding: 10px;" href="{{url('/search')}}" class="btn btn-default btn-block">Reset all filters</a>
                                    <div class="h-20"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br />

                    <div id="searchResults"></div>

                    <br />
                </div>
            @else
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-xs-12 text-left">
                            <div class="form-group">
                                <select class="form-control" name="order">
                                    <option value="Ratings">Best Ratings</option>
                                    <option value="Lowest">Price: Lowest first</option>
                                    <option value="Highest">Price: Highest first</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12">

                        </div>
                        <div class="col-md-3 col-xs-12">

                        </div>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group text-left">
                                <div class="container-2">
                                    <input type="text" id="searchCat" name="q" placeholder="{{ !empty($q) ? $q : 'Search in all categories' }}"/>
                                    <span class="icon"><i class="homeicon mdi mdi-magnify"></i></span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">

                    <div class="row">
                        <div class="col-md-3">

                            <div class="search-sidebar">
                                <div class="panel-group" id="arabic">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#arabic" href="#arabic1"
                                                   aria-expanded="true" class="">
                                                    Arabic <i class="fa fa-angle-down pull-right"></i>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="arabic1" class="panel-collapse collapse in" aria-expanded="true" style="">
                                            <div class="panel-body">
                                                <?php
                                                if (!isset($teaches)) {
                                                    $categories = array();
                                                } else {
                                                    $categories = $teaches;
                                                }
                                                $list = contentCategoryCheckbox($categories, ['1']);
                                                echo $list;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-group" id="second">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#second" href="#second1"
                                                   aria-expanded="true" class="collapsed">
                                                    Quran <i class="fa fa-angle-down pull-right"></i>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="second1" class="panel-collapse collapse" aria-expanded="false" style="">
                                            <div class="panel-body">
                                                <?php
                                                $list = contentCategoryCheckbox($categories, ['2']);
                                                echo $list;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-group" id="dialects">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#dialects" href="#dialects1"
                                                   aria-expanded="true" class="collapsed">
                                                    Arabic Dialects <i class="fa fa-angle-down pull-right"></i>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="dialects1" class="panel-collapse collapse" aria-expanded="false" style="">
                                            <div class="panel-body">
                                                <?php
                                                $list = contentCategoryCheckbox($categories, ['3']);
                                                echo $list;
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-group" id="learning">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#learning" href="#learning1"
                                                   aria-expanded="true" class="collapsed">
                                                    Learning Style <i class="fa fa-angle-down pull-right"></i>
                                                </a>
                                            </h4>
                                        </div>

                                        <?php
                                        $styles = array('1' => '1:1 Course', '2' => 'Group Course', '3' => 'Video Course');
                                        if (!isset($key_styles)) {
                                            $key_styles = array();
                                            $cls = '';
                                            $expand = 'false';
                                        } else {
                                            $cls = ' in';
                                            $expand = 'true';
                                        }
                                        ?>
                                        <div id="learning1" class="panel-collapse collapse{{$cls}}" aria-expanded="{{$expand}}" style="">
                                            <div class="panel-body">
                                                <ul>

                                                    @foreach($styles as $key=>$item)
                                                        <li><input type="checkbox" name="styles[]" value="{{$key}}"
                                                                   class="checkmark-circled" @if(in_array($key,$key_styles)){{'checked'}}@endif> {{$item}}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-group" id="language">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#language" href="#language1"
                                                   aria-expanded="true" class="collapsed">
                                                    Language <i class="fa fa-angle-down pull-right"></i>
                                                </a>
                                            </h4>
                                        </div>
                                        <?php
                                        $languages = getLanguageList();
                                        if (!isset($language)) {
                                            $language = array();
                                            $cls = '';
                                            $expand = 'false';
                                        } else {
                                            $cls = ' in';
                                            $expand = 'true';
                                        }
                                        ?>
                                        <div id="language1" class="panel-collapse collapse{{$cls}}" aria-expanded="{{$expand}}" style="">
                                            <div class="panel-body">
                                                <ul>
                                                    @foreach($languages as $item)
                                                        <li><input type="checkbox" name="language[]" value="{{$item}}"
                                                                   class="checkmark-circled" @if(in_array($item,$language)){{'checked'}}@endif> {{$item}}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="panel-group" id="gender">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#gender" href="#gender1"
                                                   aria-expanded="true" class="collapsed">
                                                    Gender <i class="fa fa-angle-down pull-right"></i>
                                                </a>
                                            </h4>
                                        </div>
                                        <?php
                                        $genders = array('Male', 'Female', 'All');
                                        if (!isset($gender)) {
                                            $gender = array();
                                            $cls = '';
                                            $expand = 'false';
                                        } else {
                                            $cls = ' in';
                                            $expand = 'true';
                                        }
                                        ?>
                                        <div id="gender1" class="panel-collapse collapse{{$cls}}" aria-expanded="{{$expand}}" style="">
                                            <div class="panel-body">
                                                <ul>

                                                    @foreach($genders as $item)
                                                        <li><input type="checkbox" name="gender[]" value="{{$item}}"
                                                                   class="checkmark-circled" @if(in_array($item,$gender)){{'checked'}}@endif> {{$item}}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-group" id="country">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#country" href="#country1"
                                                   aria-expanded="true" class="collapsed">
                                                    From Country <i class="fa fa-angle-down pull-right"></i>
                                                </a>
                                            </h4>
                                        </div>
                                        <?php
                                        $countrys = getCountryList();
                                        if (!isset($country)) {
                                            $country = array();
                                            $cls = '';
                                            $expand = 'false';
                                        } else {
                                            $cls = ' in';
                                            $expand = 'true';
                                        }
                                        ?>
                                        <div id="country1" class="panel-collapse collapse{{$cls}}" aria-expanded="{{$expand}}" style="">
                                            <div class="panel-body">
                                                <ul>
                                                    @foreach($countrys as $item)
                                                        <li><input type="checkbox" name="country[]" value="{{$item->country_code}}"
                                                                   class="checkmark-circled" @if(in_array($item->country_code,$country)){{'checked'}}@endif> {{$item->country_name}}
                                                        </li>
                                                    @endforeach
                                                    <li><input type="checkbox" name="country[]" value=""
                                                               class="checkmark-circled"> All
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-group" id="teaches">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#teaches" href="#teaches1"
                                                   aria-expanded="true" class="collapsed">
                                                    Teaches <i class="fa fa-angle-down pull-right"></i>
                                                </a>
                                            </h4>
                                        </div>
                                        <?php
                                        $student_types = array('Adults', 'Kids', 'All');
                                        if (!isset($student_type)) {
                                            $student_type = array();
                                            $cls = '';
                                            $expand = 'false';
                                        } else {
                                            $cls = ' in';
                                            $expand = 'true';
                                        }
                                        ?>
                                        <div id="teaches1" class="panel-collapse collapse{{$cls}}" aria-expanded="{{$expand}}" style="">
                                            <div class="panel-body">
                                                <ul>

                                                    @foreach($student_types as $item)
                                                        <li><input type="checkbox" name="student_type[]" value="{{$item}}"
                                                                   class="checkmark-circled" @if(in_array($item,$student_type)){{'checked'}}@endif> {{$item}}
                                                        </li>
                                                    @endforeach

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center" id="resetFilter">
                                <div class="h-20"></div>
                                <a href="{{url('/search')}}" class="btn btn-default btn-block">Reset all filters</a>
                                <div class="h-20"></div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div id="searchResults"></div>
                        </div>

                    </div>
                </div>
            @endif
                
            <div class="h-10"></div>
        </form>
        @endsection
        @section('script')
            <script>

                $('document').ready(function () {
                    refreshSearch();
                })

                function refreshSearch() {
                    $(".loading").css('display','block');
                    setTimeout(function(){
                        $(".loading").css('display','none');
                    }, 3000);

                    $.post('/ajaxSearch', $('#mainSearchForm').serialize(), function (data) {
                        $('#searchResults').html(data);
                    });
                    console.log('ok i am here');
                }

                $('#mainSearchForm').on('change', ':input', function(e){
                    refreshSearch();
                });

                $('#searchCat').keypress(function(e){
                    if ( e.which == 13 ) e.preventDefault();
                });

                var delay = (function(){
                    var timer = 0;
                    return function(callback, ms){
                        clearTimeout (timer);
                        timer = setTimeout(callback, ms);
                    };
                })();
                $('#searchCat').keyup(function() {
                    delay(function(){
                        refreshSearch();
                    }, 1000 );
                });


            </script>
@endsection
