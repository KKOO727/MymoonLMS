<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user'], function () {


    Route::group(['prefix' => 'event'], function () {
        // ...
        Route::get('fullcalendar','AppointmentController@index');
        Route::get('individualSchedule','AppointmentController@individualSchedule');
        Route::post('fullcalendar/book','AppointmentController@book');
        Route::post('fullcalendar/bookSingleTeacher','AppointmentController@bookSingleTeacher');
        Route::post('fullcalendar/unselect','AppointmentController@unselect');
        Route::post('fullcalendar/create','AppointmentController@create');
        Route::post('fullcalendar/update','AppointmentController@update');
        Route::post('fullcalendar/deletePlanBooking','AppointmentController@deletePlanBooking');
        Route::post('fullcalendar/delete','AppointmentController@removeSingleSchedule');
        Route::get('fullcalendar/delete','AppointmentController@removeSingleSchedule');
        Route::post('removeSchedule','AppointmentController@removeSchedule');
    });
    Route::group(['middleware' => ['user', 'notification']], function () {


        Route::get('dashboard', 'UserController@dashboard');
        Route::post('security/change', 'UserController@passwordChange');
        Route::post('settings/statusUpdate', 'UserController@statusUpdate');
        Route::any('dailyFeedback','UserController@dailyFeedback');
        Route::any('courseFeedback','UserController@courseFeedback');
        #################
        #### Profile ####
        #################
        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', 'UserController@userProfile');
            Route::post('store', 'UserController@userProfileStore');
            Route::post('meta/store', 'UserController@userProfileMetaStore');
            Route::post('avatar', 'UserController@userAvatarChange');
            Route::post('image', 'UserController@userProfileImageChange');
        });
        Route::group(['prefix' => 'book'], function () {
            Route::get('/', 'BookController@index');
            Route::post('/creditPay', 'BookController@creditPay');
            Route::post('/creditPaySingle', 'BookController@creditPaySingle');
            Route::get('/renewPlan', 'BookController@renewPlan');
            Route::post('/updateDonationPrice', 'BookController@updateDonationPrice');
            Route::post('/updateTotalPrice', 'BookController@updateTotalPrice');
        });

        ###################
        #### Trip Mode ####
        ###################
        Route::group(['prefix' => 'trip'], function () {
            Route::get('deactive', 'UserController@tripModeDeActive');
            Route::post('active', 'UserController@tripModeActive');
        });

        #############
        #### Buy ####
        #############
        Route::group(['prefix' => 'video'], function () {

            Route::group(['prefix' => 'buy'], function () {
                Route::get('', 'UserController@userBuyLists');
                Route::get('print/{id}', 'UserController@userBuyPrint');
                Route::post('confirm/{id}', 'UserController@userBuyConfirm');
                Route::get('rate/{id}/{rate}', 'UserController@userBuyRateStore');
            });

            Route::group(['prefix' => 'subscribe'], function () {
                Route::get('', 'UserController@subscribeList');
            });

            Route::group(['prefix' => 'off'], function () {
                Route::get('', 'UserController@userDiscounts');
                Route::get('edit/{id}', 'UserController@userDiscountEdit');
                Route::post('store', 'UserController@userDiscountStore');
                Route::post('edit/store/{id}', 'UserController@userDiscountEditStore');
                Route::get('delete/{id}', 'UserController@userDiscountDelete');
            });

            Route::group(['prefix' => 'promotion'], function () {
                Route::get('', 'UserController@promotions');
                Route::get('buy/{id}', 'UserController@promotionBuy');
                Route::post('buy/pay', 'UserController@promotionPay');
                Route::any('buy/pay/verify', 'UserController@promotionVerify');
            });

            Route::group(['prefix' => 'record'], function () {
                Route::get('', 'UserController@records');
                Route::get('edit/{id}', 'UserController@recordEdit');
                Route::get('delete/{id}', 'UserController@recordDelete');
                Route::post('store', 'UserController@recordStore');
                Route::post('edit/store/{id}', 'UserController@recordUpdate');
            });

            Route::group(['prefix' => 'request'], function () {
                Route::get('/', 'UserController@requests');
                Route::post('store', 'UserController@requestStore');
                Route::get('edit/{id}', 'UserController@requestEdit');
                Route::post('edit/store/{id}', 'UserController@requestUpdate');
                Route::post('admit', 'UserController@requestAdmit');
                Route::get('delete/{id}', 'UserController@requestDelete');
            });

            ## Live ##
            Route::group(['prefix'=>'live'], function (){
               Route::get('','UserController@videoLiveList');
               Route::get('list','UserController@videoLiveList');
               Route::post('new/store','UserController@videoLiveNewStore');
               Route::post('edit/store/{id}','UserController@videoLiveEditStore');
               Route::post('url/store/{id}','UserController@videoLiveUrlStore');
               Route::get('edit/{id}','UserController@videoLiveEdit');
               Route::get('users/{id}','UserController@videoLiveUsers');
            });

        });

        ## Article Section
        Route::group(['prefix' => 'article'], function () {
            Route::get('', 'UserController@articles');
            Route::get('list', 'UserController@articles');
            Route::get('new', 'UserController@articleNew');
            Route::post('store', 'UserController@articleStore');
            Route::get('edit/{id}', 'UserController@articleEdit');
            Route::post('edit/store/{id}', 'UserController@articleUpdate');
            Route::get('delete/{id}', 'UserController@articleDelete');
        });

        #################
        #### Channel ####
        #################
        Route::group(['prefix' => 'channel'], function () {
            Route::get('', 'UserController@channelList');
            Route::get('new', 'UserController@channelNew');
            Route::post('store', 'UserController@channelStore');
            Route::get('delete/{id}', 'UserController@channelDelete');
            Route::get('edit/{id}', 'UserController@channelEdit');
            Route::post('edit/store/{id}', 'UserController@channelUpdate');

            ## Chanel Request Section
            Route::get('request/{id}', 'UserController@channelRequest');
            Route::post('request/store', 'UserController@channelRequestStore');

            ## Chanel Video Section
            Route::get('video/{id}', 'UserController@chanelVideo');
            Route::post('video/store/{id}', 'UserController@chanelVideoStore');
            Route::get('video/delete/{id}', 'UserController@chanelVideoDelete');
        });


        Route::get('homework', 'HomeworkController@homework');
        Route::get('homework/class', 'HomeworkController@classHomework');



        #################
        #### Content ####
        #################
        Route::group(['prefix' => 'content'], function () {
            Route::get('', 'UserController@contentList');
            Route::get('delete/{id}', 'UserController@contentDelete');
            Route::get('request/{id}', 'UserController@contentRequest');
            Route::get('draft/{id}', 'UserController@contentDraft');
            Route::get('new', 'UserController@contentNew');
            Route::post('new/store', 'UserController@contentStore');
            Route::get('edit/{id}', 'UserController@contentEdit');
            Route::post('edit/store', 'UserController@contentUpdate');
            Route::post('edit/store/request/{id}', 'UserController@contentUpdateRequest');
            Route::post('edit/meta/store/{id}', 'UserController@contentMetaStore');

            Route::get('part/list/{id}', 'UserController@contentPartList');
            Route::get('part/new/{id}', 'UserController@contentPartNew');
            Route::get('part/edit/{id}', 'UserController@contentPartEdit');
            Route::get('part/delete/{id}', 'UserController@contentPartDelete');
            Route::get('part/draft/{id}', 'UserController@contentPartDraft');
            Route::get('part/request/{id}', 'UserController@contentPartRequest');
            Route::post('part/store', 'UserController@contentPartStore');
            Route::post('part/edit/store/{id}', 'UserController@contentPartUpdate');
            Route::post('publishCourse', 'UserController@publishCourse');

            Route::get('part/json/{id}', 'UserController@contentPartsJson');

            //One to One
            Route::post('save-one-form', 'UserController@contentSaveOneForm');
            Route::post('save-one-plan', 'UserController@contentSaveOnePlan');
            Route::get('generateDaysNew', 'UserController@generateDaysNew');
            Route::get('getHours', 'UserController@getHours');
            Route::get('getEditHours', 'UserController@getEditHours');
            Route::get('generateDaysEdit', 'UserController@generateDaysEdit');

            ## Meeting ##
            Route::group(['prefix'=>'meeting'], function (){
               Route::any('delete/{id}','UserController@contentMeetingDelete');
               Route::post('new/store/{id}','UserController@contentMeetingNewStore');
               Route::any('action','UserController@contentMeetingAction');
               Route::any('{id}','UserController@contentMeetingItem');
            });
        });


        #################
        #### Tickets ####
        #################
        Route::group(['prefix' => 'ticket'], function () {
            Route::get('', 'UserController@tickets');
            Route::post('store', 'UserController@ticketStore');
            Route::get('reply/{id}', 'UserController@ticketReply');
            Route::post('reply/store', 'UserController@ticketReplyStore');
            Route::get('close/{id}', 'UserController@ticketClose');
            Route::get('comments', 'UserController@ticketComments');
            Route::get('notification', 'UserController@ticketNotifications');
            Route::get('notificationClear', 'UserController@notificationClear');

            ## Support
            Route::group(['prefix' => 'support'], function () {
                Route::get('', 'UserController@ticketSupport');
                Route::get('json/{content}/{sender}', 'UserController@ticketSupportJson');
                Route::post('jsonStore', 'UserController@ticketSupportStore');
            });
        });

        ##############
        #### Sell ####
        ##############
        Route::group(['prefix' => 'balance'], function () {
            Route::get('sell/list', 'UserController@sellDownload');
            Route::get('', 'UserController@sellDownload');
            Route::get('sell/post', 'UserController@sellPost');
            Route::post('sell/post/setPostalCode', 'UserController@setPostalCode');
            Route::get('log', 'UserController@balanceLogs');
            Route::get('charge', 'UserController@balanceCharge');
            Route::post('charge/pay', 'UserController@balanceChargePay');
            Route::get('report', 'UserController@balanceReport');
        });

        ## Vimeo
        Route::group(['prefix' => 'vimeo'], function () {
            Route::any('download', 'UserController@vimeoDownload');
        });

        ## Update Notification Ajax
        Route::get('ajax/notification/{mode}/{notification_id}', function ($mode, $notification_id) {
            global $user;
            if (setNotification($user['id'], $mode, $notification_id) == 1)
                return 1;
            else
                return 0;
        });

        Route::get('profileUpgrade', 'UserController@profileUpgrade');
        Route::get('addToFavourite', 'UserController@addToFavourite');
        Route::get('favourites', 'UserController@favourites');

        Route::group(['prefix' => 'laravel-filemanager'], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });

        Route::group(['prefix' => 'quizzes'], function () {
            Route::get('/', 'UserController@QuizzesList');
            Route::post('/store', 'UserController@QuizzesStore');
            Route::get('/edit/{quiz_id}', 'UserController@QuizzesEdit');
            Route::post('/update/{quiz_id}', 'UserController@QuizzesUpdate');
            Route::get('/delete/{quiz_id}', 'UserController@QuizzesDelete');
            Route::get('/{quiz_id}/questions', 'UserController@QuizzesQuestions');
            Route::post('/{quiz_id}/questions', 'UserController@QuizzesQuestionsStore');
            Route::get('/{quiz_id}/start', 'UserController@QuizzesStart');
            Route::post('/{quiz_id}/store_results', 'UserController@QuizzesStoreResult');
            Route::get('/results/{result_id}', 'UserController@StudentQuizzesResults');
            Route::get('/{quiz_id}/results', 'UserController@QuizzesResults');
            Route::post('/results/get_descriptive', 'UserController@QuizzesResultsDescriptive');
            Route::post('/results/reviewed', 'UserController@QuizzesResultsReviewed');
        });

        Route::group(['prefix' => 'questions'], function () {
            Route::get('/{question_id}/edit', 'UserController@QuizzesQuestionsEdit');
            Route::post('/{question_id}/update', 'UserController@QuizzesQuestionsUpdate');
            Route::get('/{question_id}/delete', 'UserController@QuizzesQuestionsDelete');
        });

        Route::group(['prefix' => 'certificates'], function () {
            Route::get('/', 'UserController@CertificatesLists');
            Route::get('/{result_id}/download', 'UserController@QuizzesDownloadCertificate');
        });

        Route::group(['prefix' => 'messages'], function () {
            Route::get('', 'MessageController@messages');
            Route::post('/store', 'MessageController@messageStore');
            Route::post('/reply', 'MessageController@messageReplyStore');
            Route::post('/confirm', 'MessageController@messageConfirm');
        });
        Route::post('/payout', 'StripeController@payout');
    });
});

Route::group(['middleware' => 'notification'], function () {
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', 'UserController@dashboard');

        Route::get('active/{id}', 'UserController@userActive');
        Route::post('reset', 'UserController@forgetPassword');
        Route::get('reset/token/{token}', 'UserController@resetToken');

        Route::get('/sociliate/google', 'UserController@googleLogin');
        Route::any('/google/login', 'UserController@googleDoLogin');

        ## Register Steps ##
        Route::get('steps/one/{phone}', 'UserController@registerStepOne');
        Route::get('steps/two/{phone}/{code}', 'UserController@registerStepTwo');
        Route::get('steps/two/repeat/{phone}', 'UserController@registerStepTwoRepeat');
        Route::any('steps/three/{phone}/{code}', 'UserController@registerStepThree');


        Route::any('topUp', 'StripeController@topUpView');
        Route::post('topUpSuccess', 'StripeController@topUpSuccess');
        Route::any('createCheckoutSession', 'StripeController@createCheckoutSession');

    });

    ### Profile Section ###
    Route::get('follow/{id}', 'UserController@userFollow');
    Route::get('unfollow/{id}', 'UserController@userUnFollow');

    Route::get('profile/{id}', 'UserController@userProfileView');
    Route::post('profile/request/store', 'UserController@profileRequestStore');

    Route::group(['prefix' => 'bank'], function () {

        Route::group(['prefix' => 'paypal'], function () {
            Route::get('pay/{id}/{type}', 'UserController@paypalPay');
        });

        Route::group(['prefix' => 'paytm'], function () {
            Route::get('pay/{id}/{type}', 'UserController@paytmPay');
        });

        Route::group(['prefix' => 'payu'], function () {
            Route::get('pay/{id}/{type}', 'UserController@payuPay');
        });

        Route::group(['prefix' => 'paystack'], function () {
            Route::get('pay/{id}/{type}', 'UserController@paystackPay');
        });

        Route::get('razorpay/pay/{id}/{type}','UserController@razorpayPay');

        Route::get('wecashup/pay/{id}/{type}','UserController@wecashupPay');

        Route::get('cinetpay/pay/{id}/{type}','UserController@cinetpayPay');

        Route::get('/credit/pay/{id}/{mode}', 'UserController@creditPay');
    });
});


