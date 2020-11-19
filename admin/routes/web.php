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

Route::get('/', [ 'as' => 'login', 'uses' => 'UserController@index']);
Route::post('/', 'UserController@validateLogin');
Route::get('/dashboard', 'UserController@dashboard');
Route::get('/logout', [ 'as' => 'logout', 'uses' => 'UserController@logout']);
Route::get('/profile', 'UserController@profile');
Route::post('/profile', 'UserController@updateProfileBasic');
Route::get('/error/denied', 'ErrorController@permissionDenied');



//Users Management
Route::get('/users/list', 'UserController@userList');
Route::get('/agent/list', 'UserController@agentList');
Route::get('/admin/list', 'UserController@adminList');
Route::get('/users/add', 'UserController@addUser');
Route::post('/users/add', 'UserController@saveUser');
Route::get('/banned/list', 'UserController@bannedUsers');
Route::get('/users/update/{user_role}', 'UserController@viewUser');
Route::post('/users/update/{user_role}', 'UserController@saveUser');
Route::get('/users/deactivate/{email}', 'UserController@deactivateUser');
Route::get('/users/activate/{email}', 'UserController@activateUser');
Route::get('/users/privileges', 'UserController@userRoles');
Route::get('/users/privileges/{user_role}', 'UserController@userRolesPages');
Route::post('/users/privileges/{user_role}', 'UserController@userRolesPages');
Route::get('/users/list/detail/{email}', 'UserController@userDetail');
Route::get('/user/verify/email/{email}', 'UserController@verifyEmailAddress');



//Transactions
Route::get('/transaction/list', 'TransactionController@transactionList');
Route::get('/failed/transaction/list', 'TransactionController@failedTransactionList');
Route::get('/transaction/list/{status}', 'TransactionController@transactionListByStatus');
Route::get('/transaction/query/today/{status}', 'TransactionController@queryTodayTransactions');
Route::get('/transaction/query/service/{service_id}', 'TransactionController@queryTransactionsByServiceId');
Route::get('/transaction/approve/{ref}', 'TransactionController@approveTransaction');
Route::get('/transaction/decline/{ref}', 'TransactionController@declineTransaction');
Route::get('/transaction/stat', 'TransactionController@transactionStat');
Route::get('/transaction/list/view/{ref}', 'TransactionController@viewTransaction');
Route::get('/transaction/history/{email}', 'TransactionController@userHistory');
Route::get('/transaction/product/search', 'TransactionController@productTransactions');



//Product
Route::get('/product/sub-prod/delete/{sub_prod_id}', 'ProductController@deleteSubProduct');
Route::get('/product/sub-prod/activate/{sub_prod_id}', 'ProductController@activateSubProduct');
Route::get('/product/sub_products/add/new/{product_id}', 'ProductController@addSubProd');
Route::post('/product/sub_products/add/new/{product_id}', 'ProductController@saveSubProd');
Route::get('/product/delete/{product_id}', 'ProductController@deleteProduct');
Route::get('/product/activate/{product_id}', 'ProductController@activateProduct');
Route::get('/product/data/list', 'ProductController@dataList');
Route::get('/product/data/list/{product_id}', 'ProductController@dataList');
Route::get('/product/data/list/{product_id}/{sub_prod_id}', 'ProductController@dataList');
Route::post('/product/data/list/{product_id}/{sub_prod_id}', 'ProductController@updateSubProduct');
Route::get('/product/add/new/data', 'ProductController@addData');
Route::post('/product/add/new/data', 'ProductController@saveProduct');
Route::get('/product/recharge/list', 'ProductController@rechargeList');
Route::get('/product/recharge/list/{product_id}', 'ProductController@rechargeList');
Route::get('/product/recharge/list/{product_id}/{sub_prod_id}', 'ProductController@rechargeList');
Route::post('/product/recharge/list/{product_id}/{sub_prod_id}', 'ProductController@updateSubProduct');
Route::get('/product/add/new/recharge', 'ProductController@addRecharge');
Route::post('/product/add/new/recharge', 'ProductController@saveProduct');
Route::get('/product/pay_bills/list', 'ProductController@payBillsList');
Route::get('/product/pay_bills/list/{product_id}', 'ProductController@payBillsList');
Route::get('/product/pay_bills/list/{product_id}/{sub_prod_id}', 'ProductController@payBillsList');
Route::post('/product/pay_bills/list/{product_id}/{sub_prod_id}', 'ProductController@updateSubProduct');
Route::get('/product/add/new/pay_bills', 'ProductController@addPayBills');
Route::post('/product/add/new/pay_bills', 'ProductController@saveProduct');
Route::get('/product/buy_airtime/list', 'ProductController@buyAirtimeList');
Route::get('/product/buy_airtime/list/{product_id}', 'ProductController@buyAirtimeList');
Route::get('/product/buy_airtime/list/{product_id}/{sub_prod_id}', 'ProductController@buyAirtimeList');
Route::post('/product/buy_airtime/list/{product_id}/{sub_prod_id}', 'ProductController@updateSubProduct');
Route::get('/product/add/new/buy_airtime', 'ProductController@addBuyAirtime');
Route::post('/product/add/new/buy_airtime', 'ProductController@saveProduct');
Route::get('/product/list', 'ProductController@allSubProducts');




//Setup
Route::get('/setup/payment/account', 'SetupController@paymentAccountList');
Route::get('/setup/payment/account/{acc_no}', 'SetupController@paymentAccountList');
Route::post('/setup/payment/account/{acc_no}', 'SetupController@updatePaymentAccount');
Route::get('/setup/payment/account/add/new', 'SetupController@paymentAccountAdd');
Route::post('/setup/payment/account/add/new', 'SetupController@savePaymentAccount');
Route::get('/setup/payment/account/deactivate/{acc_no}', 'SetupController@deactivatePaymentAcct');
Route::get('/setup/charges/rate', 'SetupController@chargeRateList');
Route::get('/setup/charges/rate/{conversion_id}', 'SetupController@chargeRateList');
Route::post('/setup/charges/rate/{conversion_id}', 'SetupController@updateChargeRateList');
Route::get('/setup/charges/rate/add/new', 'SetupController@addChargesRate');
Route::post('/setup/charges/rate/add/new', 'SetupController@saveChargesRate');
Route::get('/setup/charges/rate/deactivate/{conversion_id}', 'SetupController@deactivateChargesRate');
Route::get('/setup/data/balance', 'SetupController@dataBalanceList');
Route::get('/setup/data/balance/{net_code}', 'SetupController@dataBalanceList');
Route::post('/setup/data/balance/{net_code}', 'SetupController@updateDataBalanceList');
Route::get('/setup/data/balance/add/new', 'SetupController@dataBalanceAdd');
Route::post('/setup/data/balance/add/new', 'SetupController@saveDataBalance');
Route::get('/setup/data/balance/deactivate/{net_code}', 'SetupController@deactivateDataBalance');

//Communications
Route::get('/messages/list', 'CommunicationController@messageList');
Route::get('/messages/list/{contact_id}', 'CommunicationController@messageList');
Route::post('/messages/list/{contact_id}', 'CommunicationController@saveReply');
Route::get('/message/thread/close/{support_id}', 'CommunicationController@closeMsgThread');
Route::get('/message/thread/open/{support_id}', 'CommunicationController@openMsgThread');
Route::get("/push/notification/send", 'CommunicationController@pushNotifications');
Route::get("/push/notification/re-send/{id}", 'CommunicationController@pushNotifications');
Route::get("/push/notification/send/new", 'CommunicationController@newPushNotifications');
Route::post("/push/notification/send/new", 'CommunicationController@sendPushNotifications');
Route::get("/bulk-sms/list", 'CommunicationController@bulkSms');
Route::post("/bulk-sms/list", 'CommunicationController@sendBulkSms');
Route::get("/mail/compose", 'CommunicationController@composeMail');
Route::post("/mail/compose", 'CommunicationController@sendMail');
Route::get("/mail/sent", 'CommunicationController@sentMail');
Route::get("/mail/draft", 'CommunicationController@draftMail');
Route::post("/mail/draft/save", 'CommunicationController@saveDraftMail');
Route::get("/mail/read/{id}", 'CommunicationController@readMail');


//Others
Route::get('/others/banner', 'OthersController@bannerList');
Route::get('/others/banner/{banner_id}', 'OthersController@bannerList');
Route::post('/others/banner/{banner_id}', 'OthersController@updateBanner');
Route::get('/others/banner/deactivate/{banner_id}', 'OthersController@deactivateBanner');
Route::get('/others/banner/add/new', 'OthersController@bannerAdd');
Route::post('/others/banner/add/new', 'OthersController@saveNewBanner');
Route::get("/others/faq/list", 'OthersController@faqList');
Route::get("/others/faq/deactivate/{id}", 'OthersController@deactivateFaq');
Route::get("/others/faq/list/{id}", 'OthersController@faqList');
Route::post("/others/faq/list/{id}", 'OthersController@updateFaq');
Route::get("/others/faq/add/new", 'OthersController@addFaq');
Route::post("/others/faq/add/new", 'OthersController@saveFaq');
Route::get("/others/app/settings", 'OthersController@appSettings');
Route::post("/others/app/settings", 'OthersController@updateAppSettings');
Route::get("/search", 'OthersController@searchTerm');


//Promo & Earnings
Route::get("/promo/code/list", 'PromoEarningController@promoCodeList');
Route::get("/promo/code/list/{discount_code}", 'PromoEarningController@promoCodeList');
Route::post("/promo/code/list/{discount_code}", 'PromoEarningController@updatePromoCode');
Route::get("/promo/code/add/new", 'PromoEarningController@addPromoCode');
Route::post("/promo/code/add/new", 'PromoEarningController@savePromoCode');
Route::get("/promo/code/deactivate/{discount_code}", 'PromoEarningController@deactivatePromoCode');
Route::get("/referral/earnings/list", 'PromoEarningController@referralList');
Route::get("/referral/earnings/list/{ref_code}", 'PromoEarningController@referralList');



//Wallet
Route::get("/wallet/withdrawal/request/list", 'WalletController@payoutList');
Route::get("/wallet/withdrawal/approve/{payout_id}", 'WalletController@approvePayout');
Route::get("/wallet/withdrawal/decline/{payout_id}", 'WalletController@declinePayout');
Route::get("/wallet/transaction/list", 'WalletController@walletTransactions');
Route::get("/wallet/bank/transfer/list", 'WalletController@bankTransferList');
Route::get("/wallet/card/payment/list", 'WalletController@cardPaymentList');
Route::get("/wallet/monify/payment/list", 'WalletController@monifyTransferList');
Route::get("/wallet/bank/approve/{transfer_id}", 'WalletController@approveBankTransferList');
Route::get("/wallet/bank/decline/{transfer_id}", 'WalletController@declineBankTransferList');
Route::post("/wallet/fund/add", 'WalletController@addFund');
Route::post("/wallet/fund/remove", 'WalletController@removeFund');
Route::get("/wallet/balance/list", 'WalletController@walletBalanceList');
Route::get("/wallet/settlement/list/{gateway}", 'WalletController@settlementList');
Route::get("/wallet/transaction/filter", 'WalletController@filterWalletTransaction');



//Automation
Route::get("/automation/sync", 'AutomationController@synchronize');




