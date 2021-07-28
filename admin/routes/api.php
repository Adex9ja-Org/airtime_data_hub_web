<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get("/test/receipt", 'TestController@apiTest');
Route::get("/bill_payment/re-query/{payment_ref}", 'TestController@verifyPayment');
Route::get("/bill_payment/automation-check/{payment_ref}", 'TestController@checkAutomation');
Route::get('/clear-cache', 'TestController@clearCache');
Route::get('/optimize', 'TestController@optimizeCache');

//User Profile
Route::post("/user/login", 'MobileApiController@validateLogin');
Route::post("/user/register", 'MobileApiController@registerUser');
Route::post("/user/profile/update/token", 'MobileApiController@updateUserToken');
Route::put("/user/profile/update", 'MobileApiController@updateProfileBasic');
Route::post("/user/profile/update/image", 'MobileApiController@updateProfileImage');
Route::post("/user/profile/identity/document", 'MobileApiController@updateProfileDocument');
Route::put("/user/profile/identity/bvn", 'MobileApiController@updateProfileBvn');
Route::get("/user/referral/code", 'MobileApiController@referralCode');
Route::post("/user/account/upgrade/agent", 'MobileApiController@upgradeAccount');
Route::get("/user/verify/{phone_email}", 'MobileApiController@verifyUserPhoneEmail');
Route::post("/user/verify/bank/acct_number", 'MobileApiController@verifyAccountNumber');
Route::post("/user/profile/bank/add", 'MobileApiController@addUserBank');
Route::get("/user/profile/bank/list", 'MobileApiController@getUserBanks');
Route::post("/user/profile/bank/delete", 'MobileApiController@deleteUserBank');
Route::post("/user/register/verify/phone/{phoneno}", 'MobileApiController@verifyPhoneNumber');
Route::get("/user/referrals/list", 'MobileApiController@myReferrals');
Route::post("/user/forgot_password/{email}", 'MobileApiController@forgotPassword');


//Others
Route::post("/support/message/add", 'MobileApiController@sendSupportMsg');
Route::get("/support/message/list", 'MobileApiController@supportMessages');
Route::get("/support/message/reply/list/{support_id}", 'MobileApiController@supportReplies');
Route::post("/support/message/rely/add", 'MobileApiController@saveSupportReply');
Route::get("/bank/list", 'MobileApiController@bankList');
Route::get("/banners/list", 'MobileApiController@getBannerList');
Route::get("/data/balance/code/list", 'MobileApiController@dataBalList');
Route::get("/payment/account/list", 'MobileApiController@paymentAcctList');
Route::get("/faq/list", 'MobileApiController@faqList');
Route::get("/app/settings/list", 'MobileApiController@appSettingsList');
Route::get("/discount/code/validate/{sub_prod_id}/{code}", 'MobileApiController@validateDiscountCode');
Route::get("/addons/list/{addon_code}", 'MobileApiController@addonsDetail');


//Transaction
Route::post("/transaction/post/new", 'MobileApiController@postTransaction');
Route::get("/product/transaction/list", 'MobileApiController@productTransactionList');
Route::put("/product/transaction/request/cancel/{ref}", 'MobileApiController@cancelTransaction');


//Product and Sub
Route::get("/product/list/{service_id}", 'MobileApiController@productList');
Route::get("/product/sub-product/list/{product_id}", 'MobileApiController@subProductListByProdId');
Route::get("/product/sub-product/list", 'MobileApiController@subProductList');




//Wallet
Route::get("/wallet/transaction/list", 'MobileApiController@walletTransList');
Route::post("/wallet/fund/account", 'MobileApiController@postWalletTransaction');
Route::post("/wallet/withdrawal/request", 'MobileApiController@requestPayout');
Route::post("/wallet/bank/transfer", 'MobileApiController@bankTransfer');
Route::post("/wallet/fund/transfer", 'MobileApiController@fundTransfer');
Route::get("/wallet/balance", 'MobileApiController@walletBalance');



//Automation
Route::post("/bill_payment/validate/{sub_prod_id}/{number}", 'MobileApiController@validateNumber');
Route::get('/error/denied', 'MobileApiErrorController@permissionDenied');
Route::get('/token/expired', 'MobileApiErrorController@tokenExpired');



//CallBacks
Route::post("/payment/notification/{gateway}", "CallBackController@paymentNotification");
Route::post("/settlement/notification/{gateway}", "CallBackController@settlementNotification");
