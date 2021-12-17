<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin'], function () {

    Route::get('login', 'Auth\admin\LoginController@showLoginForm');
    Route::post('login', 'Auth\admin\LoginController@login');
    Route::get('logout', 'Auth\admin\LoginController@logout');

    Route::get('/', function () {
        return redirect('/admin/report/user');
    });

    Route::group(['middleware' => 'admin'], function () {

        Route::get('profile', 'AdminController@adminProfile');
        Route::post('profile/main/update', 'AdminController@adminProfileMainUpdate');
        Route::post('profile/security/update', 'AdminController@adminProfileSecurityUpdate');

        ########################
        #### About Section #####
        ########################
        Route::get('about', 'AdminController@about');


        ########################
        ## Report & Dashboard ##
        ########################
        Route::group(['prefix' => 'report'], function () {
            Route::get('user', 'AdminController@usersReports');
            Route::get('content', 'AdminController@contentReports');
            Route::get('balance', 'AdminController@balanceReports');
        });

        #####################
        ###Manager Section###
        #####################
        Route::group(['prefix' => 'manager'], function () {
            Route::get('lists', 'AdminController@managerLists');
            Route::get('item/{id}', 'AdminController@managerShow');
            Route::post('capatibility/{id}', 'AdminController@managerCompatibility');
            Route::get('new', 'AdminController@addNewManager');
            Route::post('new/store', 'AdminController@storeNewManager');
        });

        ################
        ##User Section##
        ################
        Route::get('teacherVerify', 'AdminController@teacherVerify');
        Route::group(['prefix' => 'user'], function () {
            Route::get('lists', 'AdminController@usersLists');
            Route::get('item/{id}', 'AdminController@userShow');
            Route::post('edit/{id}', 'AdminController@userEdit');
            Route::get('password/{id}','AdminController@userPassword');
            Route::get('delete/{id}', 'AdminController@userDelete');
            Route::post('editprofile/{id}', 'AdminController@userEditProfile');
            Route::post('ratesection/{id}', 'AdminController@userRateSection');
            Route::get('ratesection/delete/{id}', 'AdminController@userRateSectionDelete');
            Route::get('vendor', 'AdminController@userVendors');
            Route::get('event/{id}', 'AdminController@userEvent');

            ## Seller & Apply


            ## Category Section
            Route::group(['prefix' => 'category'], function () {
                Route::get('', 'AdminController@userCategory');
                Route::get('edit/{id}', 'AdminController@userCategoryEdit');
                Route::post('store', 'AdminController@userCategoryStore');
                Route::post('edit/store/{id}', 'AdminController@userCategoryUpdate');
                Route::get('delete/{id}', 'AdminController@userCategoryDelete');
            });
            Route::get('incategory/{id}', 'AdminController@userInCategory');

            ## Rate Section
            Route::get('rate', 'AdminController@userRate');
            Route::post('rate/store', 'AdminController@userRateStore');
            Route::get('rate/edit/{id}/{tag}', 'AdminController@userRateEdit');
            Route::get('rate/delete/{id}/{tag}', 'AdminController@userRateDelete');

            ## User Login ##
            Route::get('userlogin/{id}', 'AdminController@loginWithUser');
        });


        ####################
        ###Ticket Section###
        ####################
        Route::group(['prefix' => 'ticket'], function () {
            Route::get('tickets', 'AdminController@tickets');
            Route::get('new', 'AdminController@ticketNew');
            Route::post('store', 'AdminController@ticketNewStore');
            Route::get('ticketsopen', 'AdminController@ticketsOpen');
            Route::get('ticketsclose', 'AdminController@ticketsClose');
            Route::get('delete/{id}', 'AdminController@ticketDelete');
            Route::get('close/{id}', 'AdminController@ticketClose');
            Route::get('open/{id}', 'AdminController@ticketOpen');
            Route::get('user/{id}', 'AdminController@ticketUser');
            Route::post('user/store', 'AdminController@ticketUserStore');
            Route::get('user/delete/{id}', 'AdminController@ticketUserDelete');


            Route::get('reply/{id}', 'AdminController@ticketReply');
            Route::get('reply/{ticketid}/edit/{id}', 'AdminController@ticketReplyEdit');
            Route::get('reply/delete/{id}', 'AdminController@ticketReplyDelete');
            Route::post('reply/store/{id}', 'AdminController@ticketStore');

            Route::get('category', 'AdminController@ticketsCategory');
            Route::get('category/edit/{id}', 'AdminController@ticketsCategoryEdit');
            Route::get('category/delete/{id}', 'AdminController@ticketsCategoryDelete');
            Route::post('category/store', 'AdminController@ticketsCategoryStore');
        });

        ##########################
        ###Notification Section###
        ##########################
        Route::group(['prefix' => 'notification'], function () {
            Route::get('list', 'AdminController@notificationLists');
            Route::get('new', 'AdminController@notificationNew');
            Route::post('store', 'AdminController@notificationListStore');
            Route::get('edit/{id}', 'AdminController@notificationEdit');
            Route::get('delete/{id}', 'AdminController@notificationDelete');
            Route::group(['prefix' => 'template'], function () {
                Route::get('/', 'AdminController@notificationTemplateLists');
                Route::get('tnew', 'AdminController@notificationTemplateNew');
                Route::get('item/{id}', 'AdminController@notificationTemplateShow');
                Route::get('delete/{id}', 'AdminController@notificationTemplateDelete');
                Route::post('edit', 'AdminController@notificationTemplateEdit');
            });
        });

        #################################################
        ########### Content ############## Section ######
        #################################################
        Route::group(['prefix' => 'content'], function () {

            Route::get('list', 'AdminController@contentLists');
            Route::get('waiting', 'AdminController@contentWaitingList');
            Route::get('draft', 'AdminController@contentDraftList');
            Route::get('user/{id}', 'AdminController@contentUserContent');
            Route::get('edit/{id}', 'AdminController@contentEdit');
            Route::get('delete/{id}', 'AdminController@contentDelete');
            Route::post('store/{id}/{mode}', 'AdminController@contentStore');
            Route::get('list/excel', 'AdminController@contentListExcel');

            ### Comment Section
            Route::get('comment', 'AdminController@contentComments');
            Route::get('comment/edit/{id}', 'AdminController@contentCommentEdit');
            Route::get('comment/delete/{id}', 'AdminController@contentCommentDelete');
            Route::get('comment/view/{action}/{id}', 'AdminController@contentCommentView');
            Route::post('comment/store', 'AdminController@contentCommentStore');

            # Support Section
            Route::get('support', 'AdminController@contentSupports');
            Route::get('support/edit/{id}', 'AdminController@contentSupportEdit');
            Route::get('support/delete/{id}', 'AdminController@contentSupportDelete');
            Route::get('support/view/{action}/{id}', 'AdminController@contentSupportView');
            Route::post('support/store', 'AdminController@contentSupportStore');

            ### Parts Section
            Route::get('edit/{id}/part/{pid}', 'AdminController@contentPartEdit');
            Route::post('partstore/{id}', 'AdminController@contentPartStore');
            Route::get('part/delete/{id}', 'AdminController@contentPartDelete');

            ## Usage
            Route::get('usage/{id}', 'AdminController@contentUsage');

            ### Category section
            Route::group(['prefix' => 'category'], function () {
                Route::get('', 'AdminController@contentCategory');
                Route::get('edit/{id}', 'AdminController@contentCategoryEdit');
                Route::post('store', 'AdminController@contentCategoryStore');
                Route::get('delete/{id}', 'AdminController@contentCategoryDelete');
                Route::get('childs/{id}', 'AdminController@contentCategoryChilds');

                ### Filter Section
                Route::group(['prefix' => 'filter'], function () {
                    Route::get('{id}', 'AdminController@contentCategoryFilter');
                    Route::get('{id}/edit/{fid}', 'AdminController@contentCategoryFilterEdit');
                    Route::post('store/{mode}', 'AdminController@contentCategoryFilterStore');
                    Route::get('delete/{id}', 'AdminController@contentCategoryFilterDelete');

                    ### Tag Section
                    Route::group(['prefix' => 'tag'], function () {
                        Route::get('{id}', 'AdminController@contentCategoryFilterTags');
                        Route::post('store/{mode}', 'AdminController@contentCategoryFilterTagNew');
                        Route::get('{id}/edit/{fid}', 'AdminController@contentCategoryFilterTagEdit');
                        Route::get('delete/{id}', 'AdminController@contentCategoryFilterTagDelete');
                    });

                });
            });

        });

        #######################
        ### Request Section ###
        #######################
        Route::group(['prefix' => 'request'], function () {
            Route::get('list', 'AdminController@requestLists');
            Route::get('delete/{id}', 'AdminController@requestDelete');
            Route::get('draft/{id}', 'AdminController@requestDraft');
            Route::get('publish/{id}', 'AdminController@requestPublish');

            ## RECORD SECTION
            Route::group(['prefix' => 'record'], function () {
                Route::get('list', 'AdminController@requestRecordList');
                Route::get('delete/{id}', 'AdminController@requestRecordDelete');
                Route::get('draft/{id}', 'AdminController@requestRecordDraft');
                Route::get('publish/{id}', 'AdminController@requestRecordPublish');
            });
        });

        ##################
        ###Blog Section###
        ##################
        Route::group(['prefix' => 'blog'], function () {
            Route::get('posts', 'AdminController@blogPosts');
            Route::get('post/new', 'AdminController@blogNewPost');
            Route::post('post/store', 'AdminController@blogStore');
            Route::get('post/edit/{id}', 'AdminController@blogEditPost');
            Route::get('post/delete/{id}', 'AdminController@blogPostDelete');

            Route::get('category', 'AdminController@blogCategory');
            Route::post('category/store', 'AdminController@blogCategoryStore');
            Route::get('category/edit/{id}', 'AdminController@blogCategoryEdit');
            Route::get('category/delete/{id}', 'AdminController@blogCategoryDelete');

            Route::get('comments', 'AdminController@blogComments');
            Route::get('comment/view/{action}/{id}', 'AdminController@blogCommentView');
            Route::post('comment/store', 'AdminController@blogCommentStore');
            Route::get('comment/edit/{id}', 'AdminController@blogCommentEdit');
            Route::get('comment/delete/{id}', 'AdminController@blogCommentDelete');
            Route::get('comment/reply/{id}', 'AdminController@blogCommentReply');
            Route::post('comment/reply/store', 'AdminController@blogCommentReplyStore');

            ## Article Section
            Route::get('article', 'AdminController@articles');
            Route::post('article/edit/store/{id}', 'AdminController@articleStore');
            Route::get('article/edit/{id}', 'AdminController@articleEdit');
            Route::get('article/delete/{id}', 'AdminController@articleDelete');
        });

        #####################
        ## Channel Section ##
        #####################
        Route::group(['prefix' => 'channel'], function () {
            Route::get('list', 'AdminController@channelLists');
            Route::post('store/{id}', 'AdminController@channelStore');
            Route::get('item/{id}', 'AdminController@channelEdit');
            Route::get('delete/{id}', 'AdminController@channelDelete');
            Route::get('excel', 'AdminController@channelExcel');
            Route::group(['prefix' => 'request'], function () {
                Route::get('/', 'AdminController@channelRequest');
                Route::get('delete/{id}', 'AdminController@channelRequestDelete');
                Route::get('draft/{id}', 'AdminController@channelRequestDraft');
                Route::get('publish/{id}', 'AdminController@channelRequestPublish');

            });
        });

        ##################
        ## Sell Section ##
        ##################
        Route::group(['prefix' => 'buysell'], function () {
            Route::get('list', 'AdminController@shoppingList');
        });

        #####################
        ## Balance Section ##
        #####################
        Route::group(['prefix' => 'balance'], function () {
            Route::get('list', 'AdminController@balanceLists');
            Route::get('new', 'AdminController@balanceCreate');
            Route::post('store', 'AdminController@balanceStore');
            Route::get('edit/{id}', 'AdminController@balanceEdit');
            Route::post('edit/store/{id}', 'AdminController@balanceUpdate');
            Route::get('list/excel', 'AdminController@balanceListsExcel');
            Route::get('delete/{id}', 'AdminController@balanceDelete');
            Route::get('withdraw', 'AdminController@balanceWithdraw');
            Route::post('confirmWithdraw', 'AdminController@confirmWithdraw');
            Route::post('withdraw/all', 'AdminController@balanceWithdrawAll');
            Route::get('withdraw/excel', 'AdminController@balanceWithdrawExcel');
            Route::get('analyzer', 'AdminController@balanceAnalyze');
            Route::get('print/{id}', 'AdminController@balancePrinter');
            Route::get('transaction', 'AdminController@transactionReports');
        });


        ###################
        ###Email Section###
        ###################
        Route::group(['prefix' => 'email'], function () {
            Route::get('new', 'AdminController@emailCreate');
            Route::get('templates', 'AdminController@emailTemplateLists');
            Route::get('template/new', 'AdminController@emailTemplateCreate');
            Route::get('template/item/{id}', 'AdminController@emailTemplateShow');
            Route::get('template/delete/{id}', 'AdminController@emailTemplateDelete');
            Route::post('template/edit', 'AdminController@emailTemplateEdit');
            Route::post('sendMail', 'AdminController@emailSendMail');
        });

        ######################
        ###Discount Section###
        ######################
        Route::group(['prefix' => 'discount'], function () {
            Route::get('list', 'AdminController@discountLists');
            Route::get('new', 'AdminController@discountCreate');
            Route::post('store', 'AdminController@discountStore');
            Route::get('edit/{id}', 'AdminController@discountEdit');
            Route::get('delete/{id}', 'AdminController@discountDelete');

            ## Content Off Section
            Route::get('contentnew', 'AdminController@discountContentCreate');
            Route::get('contentlist', 'AdminController@discountContentList');
            Route::post('content/store', 'AdminController@discountContentStore');
            Route::get('content/edit/{id}', 'AdminController@discountContentEdit');
            Route::get('content/delete/{id}', 'AdminController@discountContentDelete');
            Route::post('content/edit/store/{id}', 'AdminController@discountContentUpdate');
            Route::get('content/publish/{id}', 'AdminController@discountContentPublish');
            Route::get('content/draft/{id}', 'AdminController@discountContentdraft');
        });


        #################
        ## Ads Section ##
        #################
        Route::group(['prefix' => 'ads'], function () {
            # Plans
            Route::get('plans', 'AdminController@adsPlans');
            Route::get('newplan', 'AdminController@adsNewPlan');
            Route::post('plan/store', 'AdminController@adsNewPlanStore');
            Route::get('plan/edit/{id}', 'AdminController@adsPlanEdit');
            Route::post('plan/edit/store/{id}', 'AdminController@adsPlanEditStore');
            Route::get('plan/delete/{id}', 'AdminController@adsPlanDelete');

            #boxs
            Route::get('box', 'AdminController@adsBoxs');
            Route::get('newbox', 'AdminController@adsNewBox');
            Route::post('box/store', 'AdminController@adsBoxStore');
            Route::get('box/edit/{id}', 'AdminController@adsBoxEdit');
            Route::get('box/delete/{id}', 'AdminController@adsBoxDelete');
            Route::post('box/edit/store/{id}', 'AdminController@adsBoxUpdate');

            # Request
            Route::get('request', 'AdminController@adsRequests');

            # Vip
            Route::get('vip', 'AdminController@adsVipList');
            Route::post('vip/store', 'AdminController@adsVipStore');
            Route::get('vip/edit/{id}', 'AdminController@adsVipEdit');
            Route::post('vip/edit/store/{id}', 'AdminController@adsVipUpdate');
            Route::get('vip/delete/{id}', 'AdminController@adsVipDelete');
        });

        #####################
        ## Setting Section ##
        #####################
        Route::group(['prefix' => 'setting'], function () {
            Route::post('store/{luncher?}', 'AdminController@settingStore');
            Route::get('blog', 'AdminController@settingBlog');
            Route::get('notification', 'AdminController@settingNotification');
            Route::get('main', 'AdminController@settingMain');
            Route::get('display', 'AdminController@settingDisplay');
            Route::get('content', 'AdminController@settingContent');
            Route::get('user', 'AdminController@settingUser');
            Route::get('term', 'AdminController@settingTerm');
            Route::get('social', 'AdminController@settingSocial');
            Route::post('social/store', 'AdminController@settingSocialStore');
            Route::get('social/edit/{id}', 'AdminController@settingSocialEdit');
            Route::get('social/delete/{id}', 'AdminController@settingSocialDelete');
            Route::get('footer', 'AdminController@settingFooter');
            Route::get('pages', 'AdminController@settingPages');
            Route::get('default', 'AdminController@settingDefaults');
            Route::group(['prefix' => '/view_templates'], function () {
                Route::get('/', 'AdminController@settingViewTemplates');
                Route::post('/store', 'AdminController@settingViewTemplatesStore');
                Route::get('/toggle/{id}', 'AdminController@settingViewTemplatesToggle');
                Route::get('/delete/{id}', 'AdminController@settingViewTemplatesDelete');
            });
        });

        #####################
        ### Live Section  ###
        #####################
        Route::group(['prefix'=>'live'], function (){
           Route::get('list','AdminController@liveList');
           Route::get('details/{id}','AdminController@liveDetails');
           Route::get('delete/{id}','AdminController@liveDelete');
        });

        ######################
        ### Convert Section ##
        ######################
        Route::group(['prefix' => 'video'], function () {
            Route::get('convert/{id}', 'AdminController@videoConvert');
            Route::get('copy/{id}', 'AdminController@videoCopy');
            Route::get('preconvert/{id}', 'AdminController@videoPreConvert');
            Route::get('convertlogo/{id}', 'AdminController@videoConvertLogo');
            Route::post('screenshot', 'AdminController@videoScreenShot');

            Route::get('stream/{id}', 'AdminController@videoStreamAdmin');
        });

        Route::group(['prefix' => 'laravel-filemanager'], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });

        Route::group(['prefix' => 'quizzes'], function () {
            Route::get('/list', 'AdminController@QuizzesList');
            Route::get('/excel', 'AdminController@QuizzesListExcel');
            Route::get('/{quiz_id}/results', 'AdminController@QuizResults');
            Route::get('/{quiz_id}/results/excel', 'AdminController@QuizResultsExcel');
            Route::get('/{quiz_id}/results/{result_id}/delete', 'AdminController@QuizResultsDelete');
        });

        Route::group(['prefix' => 'certificates'], function () {
            Route::get('/list', 'AdminController@CertificatesList');
            Route::group(['prefix' => 'templates'], function () {
                Route::get('/', 'AdminController@CertificatesTemplatesList');
                Route::get('/new', 'AdminController@CertificatesNewTemplate');
                Route::post('/store', 'AdminController@CertificatesTemplateStore');
                Route::get('/preview', 'AdminController@CertificatesTemplatePreview');
                Route::get('/{template_id}/edit', 'AdminController@CertificatesTemplatesEdit');
            });
            Route::get('/{id}/download', 'AdminController@CertificatesDownload');
        });
    });
});
