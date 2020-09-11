<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout')->name('logout' );


Route::get('/', 'HomeController@index')->name('home');

// customers api calls starts here
Route::group(['prefix' => 'api_v1'], function() {
    /*
    |--------------------------------------------------------------------------
    | VERSION 1 API  Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register api routes for your version 1 of ubuy.ng. These
    |  routes are loaded by android api and only works on the app version
    | it is associated with.
    |
    */
    // customers routes begins here
    Route::group(['prefix' => 'customers'], function() {
        Route::get('home_feeds', 'DashboardController@apiIndex');
        Route::get('categories', 'DashboardController@apiCategories');
        Route::get('/my_projects/{user_id?}', 'DashboardController@apiProjects');
    });
    Route::group(['prefix' => 'auth'], function() {
        Route::get('/login/{email?}/{password?}', 'UserController@authLogin');
        Route::get('/forgetpass/{email?}', 'UserController@forgetpass');
        Route::get('/user_register/{first_name?}/{last_name?}/{email?}/{password?}/{phone?}', 'UserController@authRegister');
        Route::get('/user_profile/{id?}', 'UserController@apiProfile');
        // pro
        Route::get('/pro/user_register/{first_name?}/{last_name?}/{email?}/{password?}/{phone?}', 'UserController@authRegister');

    });
 
    
    Route::group(['prefix' => 'general'], function() {
        Route::get('/subcategories', 'DashboardController@allSubcat');
        Route::get('/search_cat', 'DashboardController@apiSearchCat');
        Route::get('/subcategories/{id?}', 'DashboardController@singleSubCategory');
        Route::get('/questions/{id?}', 'DashboardController@singleQuestion');
        Route::get('/search/subcategories', 'DashboardController@Suggestions');
        Route::get('/fetch_subcategories/{phrase?}', 'DashboardController@fetch_subcat');
        Route::get('/sudgest_cat/{cat_id?}', 'DashboardController@seachCat');

    });
    Route::group(['prefix' => 'inbox'], function() {
        Route::get('/all/{user_id?}', 'DashboardController@apiInbox');
        Route::get('/project/{user_id?}/{project_id?}', 'DashboardController@apiQuickChat');
        Route::get('/singlechat/{user_id?}/{bid_id?}', 'DashboardController@apiChat');
        Route::get('/storemessage/{sender_id?}/{bid_id?}/{project_id?}/{message?}/{receiver_id?}', 'DashboardController@apiStoreMessage');

    });

 
  /*
    |--------------------------------------------------------------------------
    | VERSION 1 API  Routes For Pros
    |--------------------------------------------------------------------------
    |
    | Here is where you can register api routes for your version 1 of ubuy.ng pro app. These
    |  routes are loaded by android api and only works on the app version
    | it is associated with.
    | 
    */

    Route::group(['prefix' => 'auth/pro'], function() {
        Route::get('/login/{email?}/{password?}', 'UserController@authLogin');
        Route::get('/forgetpass/{email?}', 'UserController@forgetpass');
        Route::get('/otp/{number?}', 'UserController@otpSms');
        Route::get('/check/otp/{number?}', 'UserController@otpChecker');
        Route::get('/user_profile/{id?}', 'UserController@apiProfile');
        Route::get('/user_register/{first_name?}/{last_name?}/{email?}/{password?}/{phone?}', 'UserController@proAuthRegister');
        Route::get('/create_profile/step2/{user_id?}/{business_name?}/{business_des?}', 'UserController@authRegister1');
        Route::get('/user_locate/step3/{user_id?}/{state?}/{address?}/{lng?}/{lat?}', 'UserController@authRegister2');
        Route::get('/user_locate/states', 'ApiProController@apiStates');

    });

    Route::group(['prefix' => '/pro/feeds'], function() {
        Route::get('pro_stats/{user_id?}', 'ApiProController@apiHomeStats');
        Route::get('categories', 'Api2Controller@apiCategories');
        Route::get('/recent_feeds/{user_id?}', 'ApiProController@apiRecentFeeds');
        Route::get('/allprojects/{user_id?}', 'ApiProController@apiProjects');
        Route::get('/allinbox/{user_id?}', 'ApiProController@apiProInbox');
        Route::get('/bids/{user_id?}', 'ApiProController@apiBids');
        Route::get('/api_notification/{user_id?}', 'ApiProController@apiNotification');
    });
    Route::group(['prefix' => '/pro/singles'], function() {
        Route::get('project/{project_id?}/{pro_id?}', 'ApiProController@singleProjectApi');
        Route::get('save_project/{project_id?}/{pro_id?}', 'ApiProController@apiSaveTask');
        Route::get('my_favorite/tasks/{pro_id?}', 'ApiProController@apiAllFavTasks');
        Route::get('bided_projects/{project_id?}/{pro_id?}', 'ApiProController@singleBidApi');
        Route::get('project_files/{project_id?}/{bid_id?}', 'ApiProController@proProjectFilesApi');
        Route::get('savebid/{project_id?}/{pro_id?}/{cus_id?}/{bid_message?}/{bid_amount?}/{bid_duration?}', 'ApiProController@sendBidApi');
        Route::get('updatebid/{bid_id?}/{pro_id?}/{bid_message?}/{bid_amount?}/{bid_duration?}', 'ApiProController@updateBidApi');
    });
    Route::group(['prefix' => '/pro/chat'], function() {
        Route::get('bid/{bid_id?}', 'ApiProController@BidChatStatusApi');
        Route::get('/messages/pro/{bid_id?}', 'ApiProController@ProcallMessage');
        Route::get('/storemessage/{sender_id?}/{bid_id?}/{project_id?}/{message?}/{receiver_id?}', 'ApiProController@apiStoreMessagePro');

    });
    Route::group(['prefix' => '/pro/profile'], function() {
        Route::get('/me/{user_id?}', 'ApiProController@profileMainApi');
        Route::get('/upay_bank_code', 'ApiProController@bankCodes');
        Route::get('/personal/details/{user_id?}', 'ApiProController@ProPersonalDetails');
        Route::get('/earnings/{user_id?}', 'ApiProController@apiupayStats');
        Route::get('/earning/pending/{user_id?}', 'ApiProController@apiPendingPaymenTasks');
        Route::get('/earning/balance/{user_id?}', 'ApiProController@apiApprovedPaymenTasks');
        Route::get('/personal/password/change/{user_id?}/{old_password?}/{new_password?}/{new_password_confirmation?}', 'ApiProController@ProPassUpdate');
        Route::get('/personal/update/{user_id?}/{first_name?}/{last_name?}/{email?}/{number?}', 'ApiProController@apiUpdateProfile');
        Route::get('/save/bank/{user_id?}/{bank_code?}/{bank_name?}/{account_name?}/{account_number?}', 'ApiProController@PostSaveBank');
        Route::get('/verify/face/{user_id?}', 'ApiProController@verifyToken');
        Route::get('/update/distance/{user_id?}/{distance?}', 'ApiProController@updateDistance');
        Route::get('/verify/checker/{user_id?}', 'ApiProController@verifyChecker');
        Route::get('/myservices/{user_id?}', 'ApiProController@proServices');
        Route::get('/delete/service/{user_id?}/{service_id?}', 'ApiProController@service_destroy');
        Route::get('/allservices', 'ApiProController@allServices');
        Route::get('/addservices/{user_id?}/{sub_category_id?}/{service_name?}', 'ApiProController@saveServices');
        Route::get('/business_details/{user_id?}', 'ApiProController@getBusiDetail');
        Route::get('/update_business/details/{user_id?}/{business_name?}/{business_des?}', 'ApiProController@updateBusi');
        Route::get('/update_business/locate/{user_id?}/{state?}/{address?}/{lng?}/{lat?}', 'ApiProController@updateBusiLocate');

        // Route::post('/post/licence/id', 'ApiProController@storeProCrd');
        // Route::get('/post/id/{user_id?}/{base_img?}/{licence_type?}/{licence_username?}/{licence_number?}/{licence_state}', 'ApiProController@storeProCrd');

    });
    Route::group(['prefix' => '/pro/config'], function() {
        Route::get('/upay_bank_code', 'ApiProController@bankCodes');
     
    });


});
// customers api calls starts here
Route::group(['prefix' => 'api_v2'], function() {
    /*
    |--------------------------------------------------------------------------
    | VERSION 2 API  Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register api routes for your version 2 of ubuy.ng. These
    |  routes are loaded by android api and only works on the app version
    | it is associated with.
    |
    */
    // customers routes begins here
    Route::group(['prefix' => 'customers'], function() {
        Route::get('home_feeds', 'Api2Controller@apiIndexV2');
        Route::get('categories', 'Api2Controller@apiCategories');
        Route::get('/my_projects/{user_id?}', 'Api2Controller@apiProjects');
        Route::get('/bids/{project_id?}', 'Api2Controller@apiProjectBids');
        Route::get('/notify/{user_id?}', 'Api2Controller@apiNotify');

        // for debugging and migrate to api_v3
        Route::get('/post/cats_state', 'Api3Controller@apiCategoriesState');
        Route::get('/pending_projects/{user_id?}', 'Api3Controller@apiPendingProjects');
        Route::get('/skills/{sub_id?}', 'Api3Controller@SubCatSkills');
        Route::get('/savedraft/qtask/{user_id?}/{project_title?}/{skill_id?}', 'Api3Controller@SaveDraftDebug');
        Route::get('/deleteDraft/{project_id?}/{skill_title?}', 'Api3Controller@deleteProjectSkill');

    });

    Route::group(['prefix' => 'auth'], function() {
        Route::get('/login/{email?}/{password?}', 'UserController@authLogin2');
        Route::get('/forgetpass/{email?}', 'UserController@forgetpass2');
        Route::get('/user_register/{first_name?}/{last_name?}/{email?}/{password?}/{phone?}', 'UserController@authRegister');
        Route::get('/user_profile/{id?}', 'UserController@apiProfile2');

    });

    
    Route::group(['prefix' => 'general'], function() {
        Route::get('/subcategories', 'Api2Controller@allSubcat');
        Route::get('/search_cat', 'Api2Controller@apiSearchCat');
        Route::get('/subcategories/{id?}', 'Api2Controller@singleSubCategory');
        Route::get('/questions/{id?}', 'Api2Controller@singleQuestion');
        Route::get('/search/subcategories', 'Api2Controller@Suggestions');
        Route::get('/fetch_subcategories/{phrase?}', 'Api2Controller@fetch_subcat');
        Route::get('/sudgest_cat/{cat_id?}', 'Api2Controller@seachCat');

    });
    Route::group(['prefix' => 'project'], function() {
        Route::get('/update_task/{project_id?}/{status?}', 'Api2Controller@updateTask');
        Route::get('/timeline/{project_id?}', 'Api2Controller@Apitracker');
        Route::get('/single_bid/{bid_id?}', 'Api2Controller@ApiSingleBid');
        Route::get('/bidstatus/{bid_id?}', 'Api2Controller@ApiBidStatus');
        Route::get('/checher/{bid_id?}', 'Api2Controller@chattest');
        Route::get('/files/{project_id?}/{bid_id?}', 'Api2Controller@ProjectFilesApi');
        Route::post('/upload/imae', 'Api2Controller@saveProjectFile');


    });
    Route::group(['prefix' => 'inbox'], function() {
        Route::get('/all/{user_id?}', 'Api2Controller@apiInbox');
        Route::get('/project/{user_id?}/{project_id?}', 'Api2Controller@apiQuickChat');
        Route::get('/singlechat/{user_id?}/{bid_id?}', 'Api2Controller@apiChat');
        Route::get('/storemessage/{sender_id?}/{bid_id?}/{project_id?}/{message?}/{receiver_id?}', 'Api2Controller@apiStoreMessage');

    });


    Route::group(['prefix' => 'profile'], function() {
        Route::get('/pro/{pro_id?}', 'Api2Controller@proBidProfile');
        Route::get('/about_me/{pro_id?}', 'Api2Controller@proAbout');
        Route::get('/portfolio/{pro_id?}', 'Api2Controller@proPortfolio');
        Route::get('/packages/{pro_id?}', 'Api2Controller@proPackages');
        Route::get('/faq/{pro_id?}', 'Api2Controller@proFaq');
     

    });
    Route::group(['prefix' => 'my'], function() {
        Route::get('/profile/{user_id?}', 'UserController@profileDetails');     
        Route::get('/edit/{user_id?}/{first_name?}/{last_name?}/{email?}/{number?}', 'Api2Controller@apiUpdateProfile');

    });
    Route::group(['prefix' => 'feedback'], function() {
        Route::get('/input/{user_id?}/{message?}', 'Api2Controller@submitFeedback');     

    });
    Route::group(['prefix' => 'config'], function() {
        Route::get('/upay/generated_ref/{bid_id?}', 'Api2Controller@apitxRef');    
        Route::get('/upay/history/{user_id?}', 'Api2Controller@apiUpay');    
        Route::get('/upay/clickpayment/{user_id?}/{project?}/{amount?}/{pro_name?}', 'Api2Controller@apiClickOnPayment');    
    });


  



});


  /* 
    * pro section begins here
    *
    */

  // pros api calls starts here
Route::group(['prefix' => 'pro_api_v1'], function() {
    /*
|--------------------------------------------------------------------------
| VERSION 1 API  Routes
|--------------------------------------------------------------------------
|
| Here is where you can register api routes for your version 1 of ubuy.ng pros app. These
|  routes are loaded by android api and only works on the app version
| it is associated with.
|
*/
    // pros routes begins here
    Route::group(['prefix' => 'pros'], function() {
        Route::get('request_feeds/{user_id?}', 'ProDashboardController@apiIndex')->middleware('cors');;
        Route::get('categories', 'DashboardController@apiCategories')->middleware('cors');;
        Route::get('/my_projects/{user_id?}', 'DashboardController@apiProjects')->middleware('cors');;
    });
    Route::group(['prefix' => 'auth'], function() {
        Route::get('/login/{email?}/{password?}', 'UserController@authLogin')->middleware('cors');;
        Route::get('/forgetpass/{email?}', 'UserController@forgetpass')->middleware('cors');;
        Route::get('/user_register/{first_name?}/{last_name?}/{email?}/{password?}/{phone?}', 'UserController@authRegister')->middleware('cors');;
        Route::get('/user_profile/{id?}', 'UserController@apiProfile')->middleware('cors');;

    });

    
    Route::group(['prefix' => 'general'], function() {
        Route::get('/subcategories', 'DashboardController@allSubcat');
        Route::get('/search_cat', 'DashboardController@apiSearchCat');
        Route::get('/subcategories/{id?}', 'DashboardController@singleSubCategory');
        Route::get('/questions/{id?}', 'DashboardController@singleQuestion');
        Route::get('/search/subcategories', 'DashboardController@Suggestions');
        Route::get('/fetch_subcategories/{phrase?}', 'DashboardController@fetch_subcat');
        Route::get('/sudgest_cat/{cat_id?}', 'DashboardController@seachCat');

    });
    Route::group(['prefix' => 'inbox'], function() {
        Route::get('/all/{user_id?}', 'DashboardController@apiInbox');
        Route::get('/project/{user_id?}/{project_id?}', 'DashboardController@apiQuickChat');
        Route::get('/singlechat/{user_id?}/{bid_id?}', 'DashboardController@apiChat');
        Route::get('/storemessage/{sender_id?}/{bid_id?}/{project_id?}/{message?}/{receiver_id?}', 'DashboardController@apiStoreMessage');

    });


  



});


// home ajax
Route::post('/fetch-subcategories-ajax', 'HomeController@fetch_subcat')->name('fetch_subcat');
Route::post('/search-subcat', 'ExploreController@search_subcat')->name('search_subcat');

Route::get('clear', 'HomeController@clearCache')->name('clear_cache');

Route::get('explore', 'ExploreController@ExploreUbuy')->name('explore_ubuy');
Route::get('category/{slug}', 'ExploreController@singleCategory')->name('category');
Route::get('sub-category/{slug}', 'ExploreController@singleSubCategory')->name('sub_category');

Route::get('new-register', 'UserController@newRegister')->name('new_register');
// for debugging
Route::get('pro-welcome-mail', 'UserController@testEmil');
Route::get('pro-Confirm-mail', 'UserController@testConfirmEmil');
Route::get('pro-project-mail', 'ProjectController@testProjectMail');
Route::get('/check', 'InboxController@userOnlineStatus');

Route::get('pro-test-mail', 'ProjectController@testMultipleEmails');
// debuggind ends here
Route::get('/pro/join-ubuy', 'UserController@registerPro')->name('register_pro');
Route::post('/pro/join-ubuy', 'UserController@registerProPost');


// email routes
Route::get('/email-verified/{email?}/{code?}', 'SettingsController@EmailConfirm');

    // pro section
Route::post('/pro/confirm-email/', 'UserController@ProConfirmMail')->name('pro_confirm_email');
Route::post('/pro/welcome-email/', 'UserController@ProWelcomeMail')->name('pro_welcome_email');
Route::post('/pro/verify-number/', 'UserController@ProVerifyNumber')->name('pro_verify_number');

Route::get('customer-register', 'UserController@registerCustomer')->name('register_customer');
Route::post('customer-register', 'UserController@registerCustomerPost')->name('register_customer_post');
// facebook auth
Route::post('/register/facebook-auth', 'UserController@FacebookRegisterCustomerPost');
Route::get('/register/facebook-auth', 'UserController@FacebookAuth');
// facebook login
Route::post('/auth/facebook-login', 'UserController@FacebookLogin')->name('fb_login_user');
// google auth
Route::post('/register/google-auth', 'UserController@GoogleRegisterCustomerPost');
Route::get('/register/google-auth', 'UserController@GoogleAuth');
// google login
Route::post('/auth/google-login', 'UserController@GoogleLogin')->name('google_login_user');



Route::get('p/{slug}', ['as' => 'single_page', 'uses' => 'PostController@showPage']);

// pages
Route::get('about-us', 'MinPageController@AboutUs')->name('about_ubuy');
Route::get('careers', 'MinPageController@Careers')->name('careers');
Route::get('how-it-works', 'MinPageController@HowItWorks')->name('htw');
Route::get('about/terms-of-use', 'MinPageController@Terms')->name('terms_of_use');
Route::get('/pro-guidelines', 'MinPageController@ProGuide')->name('pro_guide');
Route::get('/customer-guidelines', 'MinPageController@CusGuide')->name('customer_guide');
Route::get('about/privacy-policy', 'MinPageController@Privacy')->name('privacy_policy');
Route::get('/safety', 'MinPageController@Safety')->name('safety');
Route::get('/spin-how-it-works', 'MinPageController@SpinHowItWorks')->name('shtw');

// Route::get('/guarantee', 'MinPageController@Terms')->name('guarantee');
Route::get('press', 'MinPageController@Press')->name('press');


Route::get('blog', 'PostController@blogIndex')->name('blog_index');
Route::get('blog/{slug}', 'PostController@view')->name('blog_post_single');

Route::get('pricing', 'HomeController@pricing')->name('pricing');

Route::get('contact-us', 'MinPageController@contactUs')->name('contact_us');
Route::post('contact-us', 'MinPageController@contactUsPost');


//checkout
Route::get('checkout/{package_id}', 'PaymentController@checkout')->name('checkout')->middleware('auth');
Route::post('checkout/{package_id}', 'PaymentController@checkoutPost')->middleware('auth');

Route::get('payment/{transaction_id}', 'PaymentController@payment')->name('payment');
Route::post('payment/{transaction_id}', 'PaymentController@paymentPost');

Route::any('payment/{transaction_id}/success', 'PaymentController@paymentSuccess')->name('payment_success');
Route::any('payment-cancel', 'PaymentController@paymentCancelled')->name('payment_cancel');

//PayPal
Route::post('payment/{transaction_id}/paypal', 'PaymentController@paypalRedirect')->name('payment_paypal_pay');
Route::any('payment/paypal-notify/{transaction_id?}', 'PaymentController@paypalNotify')->name('paypal_notify');


Route::post('payment/{transaction_id}/stripe', 'PaymentController@paymentStripeReceive')->name('payment_stripe_receive');

Route::post('payment/{transaction_id}/bank-transfer', 'PaymentController@paymentBankTransferReceive')->name('bank_transfer_submit');

// users profile Route
Route::group(['prefix'=>'profile'], function(){
    Route::get('/user/{pro_id}/', 'ProfileController@singlePro')->name('pro_p_profile');


});

//Dashboard Route
Route::group(['prefix'=>'dashboard', 'middleware' => 'dashboard'], function(){

    Route::get('/', 'DashboardController@dashboard')->name('dashboard');
    
    Route::get('sub-category/{slug}', 'ExploreController@singleSubCategoryAuth')->name('sub_category_auth');

    Route::get('my-projects', 'ProjectController@index')->name('dash_projects');
    Route::get('saved-pros', 'SavedProsController@index')->name('dash_saved_pros');
    Route::get('my-notifications', 'DashboardController@dashboard')->name('dash_notifications');
    Route::get('my-payments-history', 'DashboardController@dashboard')->name('dash_payments_history');
    Route::get('my-account', 'UserController@proAccount')->name('dash_my_accounts');
    Route::get('/pro/my-profile', 'ProfileController@proProfile')->name('dash_my_profile');
    Route::get('my-refers', 'DashboardController@dashboard')->name('dash_my_referals');
    Route::get('my-settings', 'DashboardController@dashboard')->name('dash_my_settings');
    Route::get('/switch-to-pro', 'UserController@switchPro')->name('switch_to_pro');
    Route::post('/post-to-pro', 'UserController@postSwitchPro')->name('post_to_pro');
    Route::get('/switch-to-cus', 'UserController@switchCus')->name('switch_to_cus');
    Route::post('/post-to-cus', 'UserController@postSwitchCus')->name('post_to_cus');

    Route::group(['prefix' => 'projects'], function() {
        Route::get('update/{project_id}/', 'ProjectController@singleProject')->name('project_update');
        Route::post('update/{project_id}/', 'ProjectController@singleProjectUpdate');
        Route::post('save/project', 'ExploreController@storeResponse')->name('project_save');
        Route::post('accept/bid', 'ProjectController@acceptOffer')->name('cus_accept_offer');
        Route::get('bids/{project_id}/', 'ProjectController@ProjectBids')->name('project_bids');
    });

    Route::group(['prefix' => 'pro/requests'], function() {
        Route::get('jobs', 'RequestController@index')->name('pro_requests');

    });
    Route::group(['prefix' => 'services'], function() {
        Route::get('index', 'ServicesController@index')->name('pro_services');
        Route::get('add/services', 'ServicesController@AddServices')->name('add_services');
        Route::post('save/services', 'ServicesController@store')->name('save_services');
        Route::post('delete/services', 'ServicesController@destroy')->name('destroy_services');
        // Route::get('view/{service_id}/', 'ServicesController@singleService')->name('service_more');
        // Route::post('update/{project_id}/', 'ServicesController@singleProjectUpdate');
    });
    Route::group(['prefix' => 'reviews'], function() {
        Route::post('save/review', 'ReviewsController@store')->name('save_review');

    });



    // onboarding starts
    Route::group(['prefix' => '/onboarding'], function() {
        Route::get('/pro/welcome', 'UserController@proBoradingMain')->name('boarding_starts');
        Route::post('/cus/confirm-email/', 'UserController@CusConfirmMail')->name('cus_confirm_email');
        Route::get('/cus/welcome', 'UserController@cusConfirm')->name('cus_verify');
        Route::post('/pro/boarding1', 'UserController@onBoardingstep1')->name('pro_onboarding_1');
        Route::post('/pro/save/skill', 'UserController@saveSkill')->name('pro_save_skill');
        Route::post('/pro/skill_destroy', 'UserController@skill_destroy')->name('skill_destroy');
        Route::post('/pro/save/language', 'UserController@saveLang')->name('pro_save_language');
        Route::post('/pro/language_destroy', 'UserController@lang_destroy')->name('language_destroy');
        Route::post('/pro/save/location', 'UserController@onBoardingstepLocate')->name('pro_onboarding_locate');
        Route::post('/pro/save/profile-pic', 'UserController@saveProfilePic')->name('pro_save_profile_pic');
        Route::post('/pro/save/credentials', 'UserController@storeProCrd')->name('pro_save_cre');
        // start verify process
        Route::get('/pro/verify', 'UserController@proVerify')->name('pro_verify');
        Route::post('/pro/save/verify-pic', 'UserController@saveVerifyPic')->name('pro_save_verify_pic');
        Route::post('/cus/bookmark/pro', 'UserController@bookmarkPro')->name('save_pro_post');
        // update profile
        Route::post('/pro/update/profile', 'ProfileController@UpdateProfilePost')->name('update_pro_profile');
      // update distance
        Route::post('/pro/update/distance', 'ProfileController@UpdateProfileDistance')->name('update_pro_distance');
        
    });
    Route::group(['prefix' => '/inbox'], function() {
        Route::get('customer/my-inbox', 'InboxController@CustomerInbox')->name('customer_inbox');
        Route::get('pro/my-bids', 'InboxController@ProInbox')->name('pro_bids');
        Route::get('pro/project/{project_id}', 'InboxController@proProjectChat')->name('project_chat');
        Route::get('project/bid/{bid_id}', 'InboxController@cusProjectChat')->name('cus_project_chat');
        // customer first response to pros bid
        Route::post('/cus/respond/Bid', 'InboxController@cusFirstResponse')->name('cus_responed_bid');
        // ajax bid senders 
        Route::post('/pro/send/Bid', 'InboxController@sendBid')->name('send_bid_pro');
        // ajax message senders
        Route::post('send-message','InboxController@storeMessage');
        Route::post('/typing/','InboxController@typing');
        Route::get('/deletemessage/{id}','InboxController@deletemessage');
        Route::get('/typing-receve/{id}','InboxController@typinc_receve');
        Route::get('/chat/{id}',  'InboxController@callmessage');
        Route::get('/cus/chat/{id}',  'InboxController@callCusmessage');

        Route::get('pro/project/files/{project_id}', 'InboxController@proChatFiles')->name('project_files');
        Route::get('ajax/project/files/{project_id}', 'InboxController@proAjaxFiles')->name('ajax_files');
        Route::post('/pro/save/project-file', 'InboxController@saveProjectFile')->name('pro_save_project_file');
        Route::get('/check', 'InboxController@userOnlineStatus');


    });
    

    Route::group(['prefix' => 'account'], function() {
        Route::get('update-credentials', 'UserController@editCredentials')->name('edit_credentials');
        Route::post('update-credentials', 'UserController@updateCredentials')->name('update_cred');
        Route::post('update-user', 'UserController@updateUser')->name('update_user');
       Route::get('change-number', 'UserController@editNumber')->name('edit_number');
       Route::post('change-number', 'UserController@updateNumber');
       Route::get('change-email', 'UserController@editEmail')->name('edit_email');
       Route::post('change-email', 'UserController@updateEmail');
       Route::get('/confirm/changes', 'UserController@changeConfirm')->name('change_confirm_user');

       // Route::get('change-password', 'UserController@changePassword')->name('change_password');
       // Route::post('change-password', 'UserController@changePasswordPost');
   });

    Route::group(['middleware'=>'admin_agent_employer'], function(){


        Route::group(['prefix'=>'cms'], function(){
            Route::get('/', 'PostController@index')->name('pages');
            Route::get('page/add', 'PostController@addPage')->name('add_page');
            Route::post('page/add', 'PostController@store');

            Route::get('page/edit/{id}', 'PostController@pageEdit')->name('page_edit');
            Route::post('page/edit/{id}', 'PostController@pageEditPost');

            Route::get('posts', 'PostController@indexPost')->name('posts');
            Route::get('post/add', 'PostController@addPost')->name('add_post');
            Route::post('post/add', 'PostController@storePost');

            Route::get('post/edit/{id}', 'PostController@postEdit')->name('post_edit');
            Route::post('post/edit/{id}', 'PostController@postUpdate');

        });

    });


    Route::group(['middleware'=>'only_admin_access'], function(){

        Route::group(['prefix'=>'challenges'], function(){
            Route::get('/', ['as'=>'dashboard_challenges', 'uses' => 'ChallengesController@index']);
            Route::post('/', ['as'=>'post_challenges','uses' => 'ChallengesController@store']);

            Route::get('edit/{id}', ['as'=>'edit_challenges', 'uses' => 'ChallengesController@edit']);
            Route::get('challenge-quiz/{id}', ['as'=>'challenges_quiz', 'uses' => 'ChallengesController@quiz']);
            Route::post('edit/{id}', ['uses' => 'ChallengesController@update']);
            Route::post('editstatus/{id}', ['as'=>'challenge_status', 'uses' => 'ChallengesController@updateStatus']);

            Route::post('delete-challenges/{id}', ['as'=>'delete_challenges', 'uses' => 'ChallengesController@destroy']);
        });


        });
        });

    
        
        