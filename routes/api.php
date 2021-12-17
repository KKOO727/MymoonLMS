<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'v1'], function (){
    Route::group(['prefix'=>'duplicate'],function (){
        Route::get('email/{email}','Api\ApiController@duplicateEmail');
    });
    Route::group(['prefix'=>'upload'],function (){
        Route::get('page/{user}/{security}','Api\ApiController@uploadPage');
        Route::get('page/edit/{user}/{security}/{content_id}','Api\ApiController@uploadEditPage');
        Route::any('store/{user_id}','Api\ApiController@uploadStore');
        Route::any('edit/store/{id}/{user_id}','Api\ApiController@uploadEditStore');
        Route::any('complete/{id}/{user_id}','Api\ApiController@uploadCompleteStore');
    });
    Route::get('','Api\ApiController@functionList');
    Route::any('content','Api\ApiController@functionIndex');
    Route::any('index','Api\ApiController@functionIndex');
    Route::group(['prefix'=>'content'],function (){
        Route::get('last/{last?}','Api\ApiController@contents');
    });
    Route::any('category', 'Api\ApiController@category');

    ## Product
    Route::group(['prefix'=>'product'],function (){
       Route::any('pay','Api\ApiController@productPay');
       Route::any('verify','Api\ApiController@productVerify');
       Route::any('part','Api\ApiController@productPart');
       Route::any('comment','Api\ApiController@productComment');
       Route::any('stream','Api\ApiController@productPart');
       Route::any('search','Api\ApiController@productSearch');
       Route::any('discount','Api\ApiController@productDiscount');
       Route::any('download','Api\ApiController@productDownload');
       Route::any('support', 'Api\ApiController@productSupport');
       Route::any('{id}','Api\ApiController@product');
    });

    ## MPESA
    Route::group(['prefix'=>'mpesa'], function(){
        Route::get('pay/{id}/{mode}','Api\ApiController@mpesaPay');
        Route::any('verify','Api\ApiController@mpesaVerify');
        Route::any('confirm','Api\ApiController@mpesaConfirm');
        Route::any('ajax/{id}/{type}','Api\ApiController@mpesaAjax');
    });

    ## User Section
    Route::group(['prefix'=>'user'], function (){
        Route::any('login','Api\ApiController@userLogin');
        Route::any('info','Api\ApiController@userInformation');
        Route::any('register','Api\ApiController@userRegister');
        Route::any('remember','Api\ApiController@userRemember');
        Route::any('profile','Api\ApiController@userProfile');
        Route::any('profile/follow','Api\ApiController@userProfileFollow');
        Route::any('profile/unfollow','Api\ApiController@userProfileUnFollow');
        Route::any('setting','Api\ApiController@userSetting');

        ## Support
        Route::group(['prefix'=>'support'], function (){
            Route::post('list','Api\ApiController@supportList');
            Route::post('messages','Api\ApiController@supportMessages');
            Route::post('reply','Api\ApiController@supportReply');
            Route::post('new','Api\ApiController@supportNew');
            Route::any('action','Api\ApiController@supportAction');
            Route::any('content/reply','Api\ApiController@supportContentReply');
        });

        ## Channel
        Route::group(['prefix'=>'channel'], function (){
           Route::any('','Api\ApiController@channelList');
           Route::any('new','Api\ApiController@channelNew');
        });

        ## Courses
        Route::group(['prefix'=>'courses'], function (){
           Route::any('', 'Api\ApiController@coursesList');
        });

        ## Financial
        Route::group(['prefix'=>'financial'], function (){
            Route::any('', 'Api\ApiController@financialList');
        });

        ## Comment
        Route::group(['prefix'=>'comment'], function (){

        });

        ## Wallet
        Route::group(['prefix'=>'wallet'], function (){
           Route::any('pay','Api\ApiController@walletPay');
           Route::any('verify','Api\ApiController@walletVerify');
        });
    });

    ## Profile Section
    Route::any('profile','Api\ApiController@userProfile');

    ## Quiz Section
    Route::any('quiz/{id}','Api\ApiController@quiz');
    Route::post('quiz/{id}/store_results','Api\ApiController@quizStore');

    Route::any('time', function (){
       echo date('H:i', time());
    });
});

