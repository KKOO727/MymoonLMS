@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <?php
    if (!isset($_GET['tab'])) {
        $tab = 'profile';
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
                        <li><a href="{{URL::to('/user/profile?tab=profile')}}"><i class="fas fa-user-graduate"></i> Profile Info</a></li>
                        <li><a href="{{URL::to('/user/profile?tab=personal')}}"><i class="fas fa-user"></i> Personal Details</a></li>
                        <li><a href="{{URL::to('/user/profile?tab=security')}}"><i class="fas fa-lock"></i> Security</a></li>
                        <li><a href="{{URL::to('/user/profile?tab=settings')}}"><i class="fas fa-cogs"></i> Settings</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="light-box">
                    @if($tab == 'profile')
                        @include(getTemplate().'/user/pages/profileTab')
                    @elseif($tab == 'personal')
                        @include(getTemplate().'/user/pages/personalTab')
                    @elseif($tab == 'security')
                        @include(getTemplate().'/user/pages/securityTab')
                    @elseif($tab == 'settings')
                        @include(getTemplate().'/user/pages/settingsTab')
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="h-10"></div>
@endsection
@section('script')
    <style>
        .table > tbody > tr > td {
            vertical-align: middle;
        }
    </style>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('#lfm_avatar,#lfm_profile_image,#lfm_melli_card,#lfm_avatar_cert,#lfm_avatar_res,#lfm_avatar_pass').filemanager('file', {prefix: '/user/laravel-filemanager'});
    </script>
@endsection
