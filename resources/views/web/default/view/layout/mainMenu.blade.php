<li class="no-child"><a href="{!! baseURL() !!}">Home</a></li>
<li class="no-child"><a href="{!! baseURL() !!}/search">Find a Teacher </a></li>
<li class="no-child"><a href="{{url('/registerTeacher')}}">Become a Teacher</a></li>
<li class="no-child"><a href="https://mymoononline.com/community/rise-in-paradise/">Community</a></li>
@if(isset($user))
    @if($user->type == 'admin')
        <li class="no-child"><a href="{!! baseURL() !!}/admin/report/user"> Admin Dashboard</a></li>
    @else
        <li class="no-child"><a href="{!! baseURL() !!}/user/dashboard"> My Account</a></li>
    @endif
    <li class="no-child"><a href="{!! baseURL() !!}/logout">Logout</a></li>
@else

    <li class="no-child"><a href="{!! baseURL() !!}/login">Login/Sign Up</a></li>
@endif

<?php
$lang=strtoupper(Cookie::get('lang'));
if($lang == ""){
    $lang=strtoupper('en');
}
?>

<?php
$user_currency=currency()->getUserCurrency();
$active_currencies=currency()->getActiveCurrencies();

?>
<li class="dropdown hidden-sm hidden-xs">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="padding:16px 5px">{{$active_currencies[$user_currency]['code'].' ('.$active_currencies[$user_currency]['symbol'].')'}} <span class="caret"></span></a>
    <ul class="dropdown-menu">
        @foreach($active_currencies as $currency)
            <?php
            $cur=$currency['code'];
            ?>
            <li><a href="#" onclick="changeCurrency('{{$cur}}')">{{$currency['code']}} ({{$currency['symbol']}})</a></li>
        @endforeach
    </ul>
</li>
