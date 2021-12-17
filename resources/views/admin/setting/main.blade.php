@extends('admin.newlayout.layout',['breadcom'=>['Settings','General']])
@section('title')
    {{ trans('admin.general_settings') }}
@endsection
@section('page')

<?php $base_url= baseURL(); ?>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#main" data-toggle="tab"> {{ trans('admin.general') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="#withdraw" data-toggle="tab"> {{ trans('admin.financial') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="#factor" data-toggle="tab"> {{ trans('admin.invoice') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="#gateway" data-toggle="tab"> {{ trans('admin.payment') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="#popup" data-toggle="tab"> {{ trans('admin.popup') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="#videoAds" data-toggle="tab"> {{ trans('admin.video_ads') }} </a></li>
                <li class="nav-item"><a class="nav-link" href="#mainSlide" data-toggle="tab"> {{ trans('admin.home_hero') }} </a></li>
            </ul>
            <div class="tab-content">
                <div id="main" class="tab-pane active">
                    <form method="post" action="{{ $base_url}}/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.site_name') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="site_title" value="{{ $_setting['site_title'] ?? ''}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.site_description') }}</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="site_description">{{ $_setting['site_description'] ?? ''}}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.site_email') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="site_email" value="{{ $_setting['site_email'] ?? ''}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{!! trans('admin.site_language') !!}</label>
                            <div class="col-md-6">
                                <select name="site_language" class="form-control">
                                    @foreach(languages() as $code=>$title)
                                        <option value="{!! $code !!}" @if(isset($_setting['site_language']) and $_setting['site_language'] == $code) selected @endif>{!! $title !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{!! trans('admin.user_language_select') !!}</label>
                            <div class="col-md-6">
                                <select name="site_language_select[]" multiple class="form-control selectric" style="height: 200px;">
                                    @foreach(languages() as $code=>$title)
                                        <option value="{!! $code !!}" @if(isset($_setting['site_language_select']) && is_array(json_decode($_setting['site_language_select'],true)) && in_array($code,json_decode($_setting['site_language_select'],true))) selected @endif>{!! $title !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.fav_icon') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button type="button" id="lfm_site_fav" data-input="site_fav" data-preview="holder" class="btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="site_fav" class="form-control" dir="ltr" type="text" name="site_fav" value="{{$_setting['site_fav'] ?? ''}}" placeholder="Displays on browser (48*48px)">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="site_logo">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.logo') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button type="button" id="lfm_site_logo" data-input="site_logo" data-preview="holder" class="btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="site_logo" class="form-control" dir="ltr" type="text" name="site_logo" value="{{$_setting['site_logo'] ?? ''}}" placeholder="Displays on header (55*55px)">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="site_logo">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.logotype') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button type="button" id="lfm_site_logo_type" data-input="site_logo_type" data-preview="holder" class="btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="site_logo_type" class="form-control" dir="ltr" type="text" name="site_logo_type" dir="ltr" value="{{$_setting['site_logo_type'] ?? ''}}" placeholder="Displays on header ,Hides when scrolling. (200*55px)">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="site_logo_type">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.videos_watermark') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button type="button" id="lfm_video_watermark" data-input="video_watermark" data-preview="holder" class="btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="video_watermark" class="form-control" dir="ltr" type="text" name="video_watermark" value="{{$_setting['video_watermark'] ?? ''}}">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="video_watermark">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.requests_icon') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button type="button" id="lfm_request_page_icon" data-input="request_page_icon" data-preview="holder" class="btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="request_page_icon" class="form-control" dir="ltr" type="text" name="request_page_icon" value="{{$_setting['request_page_icon'] ?? ''}}" placeholder="Displays on requests page header (80*80px)">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="request_page_icon">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.upload_bg') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button type="button" id="lfm_upload_page_background" data-input="upload_page_background" data-preview="holder" class="btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="upload_page_background" class="form-control" dir="ltr" type="text" name="upload_page_background" value="{{$_setting['upload_page_background'] ?? ''}}" placeholder="Displays as upload page bacground (1920*1080px)">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="upload_page_background">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.login_bg') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button type="button" id="lfm_login_page_background" data-input="login_page_background" data-preview="holder" class="btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="login_page_background" class="form-control" dir="ltr" type="text" name="login_page_background" value="{{$_setting['login_page_background'] ?? ''}}" placeholder="Displays as login page bacground (1920*1080px)">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="login_page_background">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.days_graph') }}</label>
                            <div class="col-md-6">
                                <div class="input-group w-150">
                                    <input type="number" class="spinner-input form-control" name="chart_day_count" value="{{ $_setting['chart_day_count'] ?? 0 }}" maxlength="3">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="site_disable" value="0">
                                        <input type="checkbox" name="site_disable" value="1" class="custom-switch-input" @if(!empty($_setting['site_disable']) and $_setting['site_disable']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">{{ trans('admin.disable_website') }}</label>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="site_rtl" value="0">
                                        <input type="checkbox" name="site_rtl" value="1" class="custom-switch-input" @if(!empty($_setting['site_rtl']) and $_setting['site_rtl']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">RTL Layout</label>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="site_preloader" value="0">
                                        <input type="checkbox" name="site_preloader" value="1" class="custom-switch-input" @if(!empty($_setting['site_preloader']) and $_setting['site_preloader']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">{!! trans('admin.preloader') !!}</label>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="become_vendor" value="0">
                                        <input type="checkbox" name="become_vendor" value="1" class="custom-switch-input" @if(!empty($_setting['become_vendor']) and $_setting['become_vendor']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">{!! trans('admin.become_vendor') !!}</label>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="duplicate_login" value="0">
                                        <input type="checkbox" name="duplicate_login" value="1" class="custom-switch-input" @if(!empty($_setting['duplicate_login']) and $_setting['duplicate_login']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">{!! trans('admin.avoid_double_login') !!}</label>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="user_register_captcha" value="0">
                                        <input type="checkbox" name="user_register_captcha" value="1" class="custom-switch-input" @if(!empty($_setting['user_register_captcha']) and $_setting['user_register_captcha']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">{{ trans('admin.user_register_captcha') }}</label>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div id="factor" class="tab-pane">
                    <form method="post" action="{{ $base_url}}/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.approver') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-center" placeholder="Displays at the footer of financial balances" dir="ltr" name="factor_seconder" value="{{ $_setting['factor_seconder'] ?? '' }}"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.financial_manager_name') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-center" dir="ltr" name="factor_approver" placeholder="Displays at the footer of financial balances" value="{{ $_setting['factor_approver'] ?? '' }}"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.invoice_logo') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button type="button" id="lfm_factor_watermark" data-input="factor_watermark" data-preview="holder" class="btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="factor_watermark" class="form-control" dir="ltr" type="text" name="factor_watermark" dir="ltr" value="{{$_setting['factor_watermark'] ?? ''}}" placeholder="Displays on invoce and balance header">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="factor_watermark">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div id="gateway" class="tab-pane">
                    <form method="post" action="{{ $base_url}}/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="inputDefault">Currency</label>
                            <div class="col-md-8">
                                <select name="currency" class="form-control">
                                    <option value="USD" @if(get_option('currency', 'USD') == 'USD') selected @endif>United States Dollar</option>
                                    <option value="EUR" @if(get_option('currency', 'USD') == 'EUR') selected @endif>Euro Member Countries</option>
                                    <option value="AUD" @if(get_option('currency', 'USD') == 'AUD') selected @endif>Australia Dollar</option>
                                    <option value="AED" @if(get_option('currency', 'USD') == 'AED') selected @endif>United Arab Emirates dirham</option>
                                    <option value="KAD" @if(get_option('currency', 'USD') == 'KAD') selected @endif>KAD</option>
                                    <option value="JPY" @if(get_option('currency', 'USD') == 'JPY') selected @endif>Japan Yen</option>
                                    <option value="CNY" @if(get_option('currency', 'USD') == 'CNY') selected @endif>China Yuan Renminbi</option>
                                    <option value="SAR" @if(get_option('currency', 'USD') == 'SAR') selected @endif>Saudi Arabia Riyal</option>
                                    <option value="KRW" @if(get_option('currency', 'USD') == 'KRW') selected @endif>Korea (South) Won</option>
                                    <option value="INR" @if(get_option('currency', 'USD') == 'INR') selected @endif>India Rupee</option>
                                    <option value="RUB" @if(get_option('currency', 'USD') == 'RUB') selected @endif>Russia Ruble</option>
                                    --------
                                    <option value="Lek" @if(get_option('currency', 'USD') == 'Lek') selected @endif>Albania Lek</option>
                                    <option value="AFN" @if(get_option('currency', 'USD') == 'AFN') selected @endif>Afghanistan Afghani</option>
                                    <option value="ARS" @if(get_option('currency', 'USD') == 'ARS') selected @endif>Argentina Peso</option>
                                    <option value="AWG" @if(get_option('currency', 'USD') == 'AWG') selected @endif>Aruba Guilder</option>
                                    <option value="AUD" @if(get_option('currency', 'USD') == 'AUD') selected @endif>Australia Dollar</option>
                                    <option value="AZN" @if(get_option('currency', 'USD') == 'AZN') selected @endif>Azerbaijan Manat</option>
                                    <option value="BSD" @if(get_option('currency', 'USD') == 'BSD') selected @endif>Bahamas Dollar</option>
                                    <option value="BBD" @if(get_option('currency', 'USD') == 'BBD') selected @endif>Barbados Dollar</option>
                                    <option value="BYN" @if(get_option('currency', 'USD') == 'BYN') selected @endif>Belarus Ruble</option>
                                    <option value="BZD" @if(get_option('currency', 'USD') == 'BZD') selected @endif>Belize Dollar</option>
                                    <option value="BMD" @if(get_option('currency', 'USD') == 'BMD') selected @endif>Bermuda Dollar</option>
                                    <option value="BOB" @if(get_option('currency', 'USD') == 'BOB') selected @endif>Bolivia Bolíviano</option>
                                    <option value="BAM" @if(get_option('currency', 'USD') == 'BAM') selected @endif>Bosnia and Herzegovina Convertible Mark</option>
                                    <option value="BWP" @if(get_option('currency', 'USD') == 'BWP') selected @endif>Botswana Pula</option>
                                    <option value="BGN" @if(get_option('currency', 'USD') == 'BGN') selected @endif>Bulgaria Lev</option>
                                    <option value="BRL" @if(get_option('currency', 'USD') == 'BRL') selected @endif>Brazil Real</option>
                                    <option value="BND" @if(get_option('currency', 'USD') == 'BND') selected @endif>Brunei Darussalam Dollar</option>
                                    <option value="KHR" @if(get_option('currency', 'USD') == 'KHR') selected @endif>Cambodia Riel</option>
                                    <option value="CAD" @if(get_option('currency', 'USD') == 'CAD') selected @endif>Canada Dollar</option>
                                    <option value="KYD" @if(get_option('currency', 'USD') == 'KYD') selected @endif>Cayman Islands Dollar</option>
                                    <option value="CLP" @if(get_option('currency', 'USD') == 'CLP') selected @endif>Chile Peso</option>
                                    <option value="COP" @if(get_option('currency', 'USD') == 'COP') selected @endif>Colombia Peso</option>
                                    <option value="CRC" @if(get_option('currency', 'USD') == 'CRC') selected @endif>Costa Rica Colon</option>
                                    <option value="HRK" @if(get_option('currency', 'USD') == 'HRK') selected @endif>Croatia Kuna</option>
                                    <option value="CUP" @if(get_option('currency', 'USD') == 'CUP') selected @endif>Cuba Peso</option>
                                    <option value="CZK" @if(get_option('currency', 'USD') == 'CZK') selected @endif>Czech Republic Koruna</option>
                                    <option value="DKK" @if(get_option('currency', 'USD') == 'DKK') selected @endif>Denmark Krone</option>
                                    <option value="DOP" @if(get_option('currency', 'USD') == 'DOP') selected @endif>Dominican Republic Peso</option>
                                    <option value="XCD" @if(get_option('currency', 'USD') == 'XCD') selected @endif>East Caribbean Dollar</option>
                                    <option value="EGP" @if(get_option('currency', 'USD') == 'EGP') selected @endif>Egypt Pound</option>
                                    <option value="GTQ" @if(get_option('currency', 'USD') == 'GTQ') selected @endif>Guatemala Quetzal</option>
                                    <option value="HKD" @if(get_option('currency', 'USD') == 'HKD') selected @endif>Hong Kong Dollar</option>
                                    <option value="HUF" @if(get_option('currency', 'USD') == 'HUF') selected @endif>Hungary Forint</option>
                                    <option value="IDR" @if(get_option('currency', 'USD') == 'IDR') selected @endif>Indonesia Rupiah</option>
                                    <option value="IRR" @if(get_option('currency', 'USD') == 'IRR') selected @endif>Iran Rial</option>
                                    <option value="ILS" @if(get_option('currency', 'USD') == 'ILS') selected @endif>Israel Shekel</option>
                                    <option value="LBP" @if(get_option('currency', 'USD') == 'LBP') selected @endif>Lebanon Pound</option>
                                    <option value="MYR" @if(get_option('currency', 'USD') == 'MYR') selected @endif>Malaysia Ringgit</option>
                                    <option value="NGN" @if(get_option('currency', 'USD') == 'NGN') selected @endif>Nigeria Naira</option>
                                    <option value="NOK" @if(get_option('currency', 'USD') == 'NOK') selected @endif>Norway Krone</option>
                                    <option value="OMR" @if(get_option('currency', 'USD') == 'OMR') selected @endif>Oman Rial</option>
                                    <option value="PKR" @if(get_option('currency', 'USD') == 'PKR') selected @endif>Pakistan Rupee</option>
                                    <option value="PHP" @if(get_option('currency', 'USD') == 'PHP') selected @endif>Philippines Peso</option>
                                    <option value="PLN" @if(get_option('currency', 'USD') == 'PLN') selected @endif>Poland Zloty</option>
                                    <option value="RON" @if(get_option('currency', 'USD') == 'RON') selected @endif>Romania Leu</option>
                                    <option value="ZAR" @if(get_option('currency', 'USD') == 'ZAR') selected @endif>South Africa Rand</option>
                                    <option value="LKR" @if(get_option('currency', 'USD') == 'LKR') selected @endif>Sri Lanka Rupee</option>
                                    <option value="SEK" @if(get_option('currency', 'USD') == 'SEK') selected @endif>Sweden Krona</option>
                                    <option value="CHF" @if(get_option('currency', 'USD') == 'CHF') selected @endif>Switzerland Franc</option>
                                    <option value="THB" @if(get_option('currency', 'USD') == 'THB') selected @endif>Thailand Baht</option>
                                    <option value="TRY" @if(get_option('currency', 'USD') == 'TRY') selected @endif>Turkey Lira</option>
                                    <option value="UAH" @if(get_option('currency', 'USD') == 'UAH') selected @endif>Ukraine Hryvnia</option>
                                    <option value="GBP" @if(get_option('currency', 'USD') == 'GBP') selected @endif>United Kingdom Pound</option>
                                    <option value="TWD" @if(get_option('currency', 'USD') == 'TWD') selected @endif>Taiwan New Dollar</option>
                                    <option value="VND" @if(get_option('currency', 'USD') == 'VND') selected @endif>Viet Nam Dong</option>
                                    <option value="UZS" @if(get_option('currency', 'USD') == 'UZS') selected @endif>Uzbekistan Som</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="gateway_paypal" value="0">
                                        <input type="checkbox" name="gateway_paypal" value="1" class="custom-switch-input" @if(!empty($_setting['gateway_paypal']) and $_setting['gateway_paypal']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">Paypal</label>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="gateway_paystack" value="0">
                                        <input type="checkbox" name="gateway_paystack" value="1" class="custom-switch-input" @if(!empty($_setting['gateway_paystack']) and $_setting['gateway_paystack']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">Paystack</label>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="gateway_paytm" value="0">
                                        <input type="checkbox" name="gateway_paytm" value="1" class="custom-switch-input" @if(!empty($_setting['gateway_paytm']) and $_setting['gateway_paytm']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">Paytm</label>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="gateway_payu" value="0">
                                        <input type="checkbox" name="gateway_payu" value="1" class="custom-switch-input" @if(!empty($_setting['gateway_payu']) and $_setting['gateway_payu']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">Payu</label>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="gateway_razorpay" value="0">
                                        <input type="checkbox" name="gateway_razorpay" value="1" class="custom-switch-input" @if(!empty($_setting['gateway_razorpay']) and $_setting['gateway_razorpay']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">Razorpay</label>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="gateway_cinetpay" value="0">
                                        <input type="checkbox" name="gateway_cinetpay" value="1" class="custom-switch-input" @if(!empty($_setting['gateway_cinetpay']) and $_setting['gateway_cinetpay']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">Cinetpay</label>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="withdraw" class="tab-pane">
                    <form method="post" action="{{ $base_url}}/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.open_courses_comm') }}</label>
                            <div class="col-md-3">
                                <input type="number" class="spinner-input form-control" name="site_income" value="{{ $_setting['site_income'] ?? 0 }}" maxlength="3">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.exclusive_courses_comm') }}</label>
                            <div class="col-md-3">
                                <input type="number" class="spinner-input form-control" name="site_income_private" value="{{ $_setting['site_income_private'] ?? 0 }}" maxlength="3">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.min_withdrawal_amount') }}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="site_withdraw_price" value="{!! get_option('site_withdraw_price',0) !!}" class="form-control text-center numtostr">
                                    <span class="input-group-append click-for-upload cu-p">
                              <span class="input-group-text">{!! currencySign() !!}</span>
                           </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div id="popup" class="tab-pane">
                    <form method="post" action="{{ $base_url}}/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="site_popup" value="0">
                                        <input type="checkbox" name="site_popup" value="1" class="custom-switch-input" @if(!empty($_setting['site_popup']) and $_setting['site_popup']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">{{ trans('admin.popup') }}</label>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.popup_image') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button type="button" id="lfm_popup_image" data-input="popup_image" data-preview="holder" class="btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="popup_image" class="form-control" dir="ltr" type="text" name="popup_image" dir="ltr" value="{{$_setting['popup_image'] ?? ''}}" >
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="popup_image">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.popup_link') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-center" dir="ltr" name="popup_url" value="{{ $_setting['popup_url'] ?? '' }}"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div id="videoAds" class="tab-pane">
                    <form method="post" action="{{ $base_url}}/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="custom-switches-stacked">
                                    <label class="custom-switch">
                                        <input type="hidden" name="site_videoads" value="0">
                                        <input type="checkbox" name="site_videoads" value="1" class="custom-switch-input" @if(!empty($_setting['site_videoads']) and $_setting['site_videoads']==1) checked @endif />
                                        <span class="custom-switch-indicator"></span>
                                        <label class="custom-switch-description" for="inputDefault">{{ trans('admin.enable') }}</label>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Xml {{ trans('admin.video_file') }} Url</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" placeholder="https://" name="site_videoads_source" dir="ltr" value="{{$_setting['site_videoads_source'] ?? ''}}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{!! trans('admin.text') !!}</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="site_videoads_title" dir="ltr" value="{{$_setting['site_videoads_title'] ?? ''}}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">Roll Type</label>
                            <div class="col-md-6">
                                <select name="site_videoads_roll_type" class="form-control">
                                    <option value="preRoll" @if(get_option('site_videoads_roll_type','') == 'preRoll') selected @endif>PreRoll</option>
                                    <option value="midRoll" @if(get_option('site_videoads_roll_type','') == 'midRoll') selected @endif>MidRoll</option>
                                    <option value="postRoll" @if(get_option('site_videoads_roll_type','') == 'postRoll') selected @endif>PostRoll</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.minimum_time_to_skip') }}</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="number" class="spinner-input form-control text-center" name="site_videoads_time" value="{{ $_setting['site_videoads_time'] ?? 0 }}" maxlength="3">
                                    <span class="input-group-append"><label class="input-group-text">Seconds</label></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div id="mainSlide" class="tab-pane">
                    <form method="post" action="{{ $base_url}}/admin/setting/store" class="form-horizontal form-bordered">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-3 control-label">{{ trans('admin.hero_bg') }}</label>
                            <div class="col-md-6">
                                <div class="input-group" style="display: flex">
                                    <button type="button" id="lfm_main_page_slide" data-input="main_page_slide" data-preview="holder" class="btn btn-primary">
                                        Choose
                                    </button>
                                    <input id="main_page_slide" class="form-control" dir="ltr" type="text" name="main_page_slide" dir="ltr" value="{{$_setting['main_page_slide'] ?? ''}}" placeholder="Displays as homepage header background (1920*500px)">
                                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="main_page_slide">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-center" name="main_page_slide_title" value="{{ $_setting['main_page_slide_title'] ?? '' }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.description') }}</label>
                            <div class="col-md-6">
                                <textarea rows="5" class="form-control text-center" name="main_page_slide_text">{{ $_setting['main_page_slide_text'] ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.first_button') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-center" name="main_page_slide_btn_1_text" value="{{ $_setting['main_page_slide_btn_1_text'] ?? '' }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.second_button') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-center" name="main_page_slide_btn_2_text" value="{{ $_setting['main_page_slide_btn_2_text'] ?? '' }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.first_button_link') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-center" name="main_page_slide_btn_1_url" value="{{ $_setting['main_page_slide_btn_1_url'] ?? '' }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.secound_button_link') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-center" name="main_page_slide_btn_2_url" value="{{ $_setting['main_page_slide_btn_2_url'] ?? '' }}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ $base_url}}/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('#lfm_site_fav,#lfm_site_logo,#lfm_site_logo_type,#lfm_video_watermark,#lfm_request_page_icon,#lfm_upload_page_background,#lfm_login_page_background,#lfm_factor_watermark,#lfm_popup_image,#lfm_main_page_slide').filemanager('image');
    </script>
@endsection
