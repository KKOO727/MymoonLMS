<?php

use Illuminate\Support\Facades\Route;

Route::get('/dataMigration', 'PaypalController@dataMigration');
Route::get('/fixCourses', 'MessageController@fixCourses');


Route::get('/cronClearTempBooking', 'CronController@cronClearTempBooking');
Route::get('/cronClearContent', 'CronController@cronClearContent');
Route::get('/cronDeactivateTeacher', 'CronController@cronDeactivateTeacher');
Route::get('/cronRenewPlan', 'CronController@cronRenewPlan');
Route::get('/fixSchedules', 'CronController@fixSchedules');
Route::post('/contact', 'WebController@contact');



Route::group(['namespace' => 'Auth'], function () {
    // Web Auth Routes
    Route::get('/login', 'LoginController@showLoginForm');
    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout');

   // Route::get('/register', 'RegisterController@showRegistrationForm');
   // Route::post('/registerUser', 'RegisterController@register');

    //Route::get('/registerUser', 'RegisterController@registerUser');
	Route::get('/registerTeacher', 'RegisterController@registerTeacher');
    Route::get('/registerStudent', 'RegisterController@registerStudent');
    Route::get('/resetPassword', 'RegisterController@resetPassword');
    Route::post('/registerUser', 'RegisterController@register');
});

Route::group(['middleware' => 'notification'], function () {
    Route::get('/', 'WebController@home')->name('home');

    Route::get('category/{id}', 'WebController@category');
    Route::get('category', 'WebController@category');

    Route::get('/teachers', 'WebController@teachers');

    Route::get('search', 'WebController@search');

    Route::post('search', 'WebController@search');
    Route::get('jsonsearch', 'WebController@jsonSearch');
    Route::post('ajaxSearch', 'WebController@ajaxSearch');

    Route::get('booking/{id}', 'WebController@booking');
    Route::get('faqs', 'WebController@faqs');


    Route::get('/stripe-payment', 'StripeController@handleGet');
    Route::post('/stripe-payment', 'StripeController@handlePost')->name('stripe.payment');
    Route::post('/create-payment-intent', 'StripeController@createPaymentIntent');


    ## Blog Section ##
    Route::group(['prefix' => 'blog'], function () {
        Route::get('/', 'WebController@blog');
        Route::get('post/{id}', 'WebController@blogPost');
        Route::get('mobile/{id}', 'WebController@blogPostMobile');
        Route::get('category/{id}', 'WebController@blogCategory');
        Route::post('post/comment/store', 'WebController@blogPostCommentStore');
        Route::get('tag/{key}', 'WebController@blogTags');
    });

    ## Gift & Off
    Route::get('gift/{code}', 'WebController@giftChecker');

    ## Chanel Section
    Route::group(['prefix' => 'chanel'], function () {
        Route::get('{username}', 'WebController@chanel');
        Route::get('follow/{id}', 'WebController@chanelFollow');
        Route::get('unfollow/{id}', 'WebController@chanelUnFollow');
    });

    ## Page Section ##
    Route::group(['prefix' => 'page'], function () {
        Route::get('{key}', 'WebController@page');
    });

    ### Product Section ###
    Route::group(['prefix' => 'product'], function () {
        Route::get('{id}', 'WebController@product');
        Route::get('part/{id}/{pid}', 'WebController@productPart');
        Route::get('captivate/{id}/{pid}','WebController@productCaptivate');
        ## Comment & Support
        Route::post('comment/store/{id}', 'WebController@productCommentStore');
        Route::post('support/store', 'WebController@productSupportStore');
        Route::get('support/rate/{id}/{rate}', 'WebController@productSupportRate');
        ## Favorite ##
        Route::get('fav/{id}', 'WebController@productFavorite');
        Route::get('unfav/{id}', 'WebController@productUnFavorite');
        Route::get('{id}/rate/{rate}', 'WebController@productRate');
        ## Subscribe ##
        Route::get('subscribe/{id}/{type}/{payMode}', 'WebController@productSubscribe');
    });

    ## Article Section
    Route::group(['prefix' => 'article'], function () {
        Route::get('/list', 'WebController@articles');
        Route::get('item/{id}', 'WebController@articleShow');
    });

    ## Request Section
    Route::group(['prefix' => 'request'], function () {
        Route::get('', 'WebController@requests');
        Route::get('new', 'WebController@newRequest');
        Route::post('store', 'WebController@storeRequest');
        Route::get('follow/{id}', 'WebController@followRequest');
        Route::get('unfollow/{id}', 'WebController@unFollowRequest');
        Route::get('suggestion/{id}/{suggest}', 'WebController@suggestionRequest');
    });

    ### Record Section ###
    Route::group(['prefix' => 'record'], function () {
        Route::get('', 'WebController@records');
        Route::get('follow/{id}', 'WebController@recordFollow');
        Route::get('unfollow/{id}', 'WebController@recordUnFollow');
    });

    ## Video Section ##
    Route::group(['prefix' => 'video'], function () {
        Route::get('stream/{id}', 'WebController@videoStream');
        Route::get('download/{id}', 'WebController@videoDownload');
    });
    Route::get('/progress', 'WebController@videoProgress');

    Route::get('login/{user}', 'WebController@loginTrack');

    ## Usage
    Route::get('usage/{product}/{user}', 'WebController@usageTrack');

    Route::any('payment/wallet/status', 'WebController@walletStatus');


    ### Bank Section ###
    Route::group(['prefix' => 'bank'], function () {

        Route::group(['prefix' => 'paypal'], function () {
            Route::any('status', 'WebController@paypalStatus');
            Route::any('cancel/{id}', 'WebController@paypalCancel');
        });

        Route::group(['prefix' => 'paytm'], function () {
            Route::any('status/{product_id}', 'WebController@paytmStatus');
            Route::any('cancel/{id}', 'WebController@paytmCancel');
        });

        Route::group(['prefix' => 'payu'], function () {
            Route::any('status/{product_id}', 'WebController@payuStatus');
            Route::any('cancel/{id}', 'WebController@payuCancel');
        });

        Route::group(['prefix' => 'paystack'], function () {
            Route::any('status/{id}', 'WebController@paystackStatus');
            Route::any('cancel/{id}', 'WebController@paystackCancel');
        });

        Route::group(['prefix' => 'razorpay'], function () {
            Route::any('status/{id}', 'WebController@razorpayStatus');
        });

        Route::group(['prefix' => 'wecashup'], function (){
            Route::any('callback','WebController@wecashupCallback');
            Route::any('hook','WebController@wecashupHook');
        });

        Route::group(['prefix' => 'cinetpay'], function (){
            Route::any('notify','WebController@cinetpaynotify');
            Route::any('return','WebController@cinetpayReturn');
            Route::any('cancel','WebController@cinetpayCancel');
        });
    });

});

Route::get('update',function(){
   $users = \App\User::all();
   foreach ($users as $user){
       try {
           $password = decrypt($user->password);
           \App\User::find($user->id)->update(['password'=>\Illuminate\Support\Facades\Hash::make($password)]);
       } catch(\RuntimeException $e) {
       }
   }
});

Route::any('localeUpdate','WebController@localeUpdate');
Route::any('currencyUpdate','WebController@currencyUpdate');
