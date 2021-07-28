<?php

namespace App\Http\Controllers;

use App\Jobs\HandleTransactionJob;
use App\Model\ActiveStatus;
use App\Model\JsonResponse;
use App\Model\PaymentMethod;
use App\Model\RequestStatus;
use App\Model\Services;
use App\Model\TransactionType;
use Illuminate\Http\Request;

class MobileApiController extends Controller
{
    private $agent_fee = 5000;
    public function __construct()
    {
        $this->middleware(['request_encryption']);
        $this->middleware(['mobile_auth'])->except('receiptTest', 'validateLogin', 'verifyPhoneNumber', 'registerUser', 'forgotPassword');
        parent::__construct();
    }
    private function getUserWithJwt($email, $msg = null, $user = null)
    {
        $user = $user == null ? $this->mproxy->getUserByEmail($email) : $user;
        if($user != null){
            unset($user->userRole);
            unset($user->active);
            unset($user->token);
            unset($user->is_email_verified);
            unset($user->ref_code);
            unset($user->created_at);
            unset($user->updated_at);
            $user = $this->mproxy->addJwtToUser($user);
            $user->bank_name = 'Wema bank';
            return json_encode(new JsonResponse("00", $msg, $user));
        }
        else
            return json_encode(new JsonResponse("-01", "User does not exist"));
    }
    private function updateProfile($inputs)
    {
        $this->mproxy->updateUser($inputs, $inputs['email']);
        return $this->getUserWithJwt($inputs['email'], 'Profile Updated Successfully!');
    }
    public function validateLogin(Request $request)
    {
        $inputs = $request->input();
        $user = $this->mproxy->validateUser($inputs);
        if($user != null){
            if($user->is_email_verified == 1){
                if($user->active == 1){
                    $user->account_number = $this->mproxy->getAccountReserved($user);
                    $user->remember_token = $this->mproxy->generateRememberMeToken($user);
                    return $this->getUserWithJwt($user->email, 'Login Successfully!', $user);
                }
                else
                    return json_encode(new JsonResponse("-01", "Your account is banned contact support@airtimedatahub.com"));
            }
            else
                return json_encode(new JsonResponse("-01", "Unverified Email Account!"));
        }
        else
            return json_encode(new JsonResponse("-01", "Invalid Username/Password!"));

    }
    public function registerUser(Request $request)
    {
        $inputs = $request->input();
        if($inputs['fullname'] != '' && $inputs['email'] != '' && $inputs['phoneno'] != '' && $inputs['address'] != '' && $inputs['password'] != ''){
            if (filter_var($inputs['email'], FILTER_VALIDATE_EMAIL)) {
                $user = $this->mproxy->getUserByEmailPhoneBvn($inputs['phoneno']);
                if($user == null){
                    $this->mproxy->saveNewUser($inputs);
                    $user = $this->mproxy->getUserByEmail($inputs['email']);
                    if($user != null) {
                        $this->mproxy->sendRegMail($user);
                        return json_encode(new JsonResponse("00", "Registration Successful...Kindly Check your email for a verification link!!"));
                    }
                    else
                        return json_encode(new JsonResponse("-01", "Email already exists"));
                }
                else
                    return json_encode(new JsonResponse("-01", "Phone number already exists"));
            }
            else
                return json_encode(new JsonResponse("-01", "Invalid email address"));
        }
        else
            return json_encode(new JsonResponse("-01", "All Field(s) are required!"));
    }
    public function verifyPhoneNumber(Request $request, $phoneno){
        $user = $this->mproxy->getUserByEmailPhoneBvn($phoneno);
        $code = $user == null ? "00" : "-01";
        $msg = $user == null ? "Verification Successful!" : "Phone Number already exist(s)";
        return json_encode(new JsonResponse($code, $msg, $phoneno));
    }
    public function updateUserToken(Request $request)
    {
        $inputs = $request->input();
        $email = $this->mproxy->getEmailFromJwt($request);
        $this->mproxy->updateUserToken($inputs, $email);
        $user = $this->mproxy->getUserByEmail($email);
        if($user->account_number == null || $user->account_number == ''){
            $this->mproxy->reQueryReservedAccount($user);
        }
        return $this->getUserWithJwt($email, 'Token Updated!');
    }
    public function updateProfileBasic (Request $request){
        $inputs = $request->input();
        $inputs['email'] = $this->mproxy->getEmailFromJwt($request);
        return $this->updateProfile($inputs);
    }
    public function updateProfileImage (Request $request){
        $email = $this->mproxy->getEmailFromJwt($request);
        $this->mproxy->updateUserImage($request->file('fileToUpload'), $email);
        return $this->getUserWithJwt($email, 'Uploaded Successfully!');
    }
    public function updateProfileDocument (Request $request){
        $email = $this->mproxy->getEmailFromJwt($request);
        $this->mproxy->updateProfileDocument($request->file('fileToUpload'), $email, $request->input('doc_type'));
        return $this->getUserWithJwt($email, 'Uploaded Successfully!');
    }
    public function updateProfileBvn (Request $request){
        $inputs = $request->input();
        $user = $this->mproxy->getUserByEmailPhoneBvn($inputs['bvn_number']);
        if($user == null){
            if($this->mproxy->verifyBvn($inputs)){
                $bvnDetail = $this->mproxy->getBvnDetail($inputs['bvn_number']);
                $inputs = [];
                $inputs['fullname'] = $bvnDetail->last_name . ' ' . $bvnDetail->first_name;
                $inputs['dob'] = $bvnDetail->bvn_dob;
                $inputs['phoneno'] = $bvnDetail->bvn_phone;
                $inputs['bvn_number'] = $bvnDetail->bvn_number;
                $inputs['email'] = $this->mproxy->getEmailFromJwt($request);
                return $this->updateProfile($inputs);
            }
            else
                return json_encode(new JsonResponse("-01", "BVN Verification Failed", $inputs));
        }
        else
            return json_encode(new JsonResponse("-01", "BVN already used", $inputs));
    }
    public function verifyUserPhoneEmail(Request $request, $phone_email){
        $user = $this->mproxy->getUserByEmailPhoneBvn($phone_email);
        $code = $user == null ? "-01" : "00";
        $message = $user == null ? "User not found!" : "User found!";
        return json_encode(new JsonResponse($code, $message, $user));
    }
    public function referralCode(Request $request){
        $email = $this->mproxy->getEmailFromJwt($request);
        $referral = $this->mproxy->getReferralByEmail($email);
        return json_encode(new JsonResponse("00", null, $referral));
    }
    public function myReferrals(Request $request){
        $email = $this->mproxy->getEmailFromJwt($request);
        $referral = $this->mproxy->getReferralByEmail($email);
        if($referral != null){
            $earnings = $this->mproxy->getReferralEarnings($referral['ref_code']);
            if($earnings != null && sizeof($earnings) > 0)
                return json_encode(new JsonResponse("00", null, $earnings));
            else{
                $ref_code = $referral['ref_code'];
                return json_encode(new JsonResponse("-01", "Oops, you have no referral yet, share your referral code [$ref_code] to others to get referrals"));
            }
        }
        else
            return json_encode(new JsonResponse("-01", "Oops, You have no referral code yet, kindly upgrade your account to an agent to get a personalized referral code."));
    }
    public function upgradeAccount(Request $request){
        $email = $this->mproxy->getEmailFromJwt($request);
        $balance = $this->mproxy->getWalletBalance($email);
        if($balance >= $this->agent_fee){
            $reference = $this->mproxy->generateRef();
            $inputs['amount'] = $this->agent_fee;
            $inputs['email'] = $email;
            $inputs['reference'] = $reference;
            $inputs['ref_code'] = $this->mproxy->genReferralCode($email, $reference);
            $this->mproxy->saveReferralCode($inputs);
            $referral = $this->mproxy->getReferralByEmail($email);
            $code = $referral == null ? "-01" : "00";
            $msg = $referral == null ? "Error Occurs!" : "Your account upgrade is successful!";
        }
        else{
            $code = "-01";
            $msg = "Insufficient Fund!";
        }
        return json_encode(new JsonResponse($code, $msg, $referral ?? null));
    }
    public function sendSupportMsg (Request $request){
        $email = $this->mproxy->getEmailFromJwt($request);
        $inputs = $request->input();
        $inputs['email'] = $email;
        $this->mproxy->sendSupportMsg($inputs);
        $contact = $this->mproxy->getSupportMsgById($inputs['support_id']);
        $code = $contact == null ? "-01" : "00";
        $msg = $contact == null ? "Error Occurs" : "Message Submitted!";
        return json_encode(new JsonResponse($code, $msg, $contact));
    }
    public function bankList(){
        $bankList = $this->mproxy->getBankList();
        return json_encode(new JsonResponse("00", null, $bankList));
    }
    public function postTransaction(Request $request){
        $inputs = $request->input();
        $email = $this->mproxy->getEmailFromJwt($request);
        $user = $this->mproxy->getUserByEmail($email);
        $subProduct = $this->mproxy->getSubProductDetail($inputs['sub_prod_id']);
        if($subProduct->active == ActiveStatus::Active){
            if($inputs['amount'] > 0){
                if($inputs['service_id'] == Services::Airtime2Cash &&  empty($user->bvn_number))
                    return json_encode(new JsonResponse("-01", "Account not verified!"));
                else{
                    $transaction = $this->mproxy->postTransaction($inputs , $user, $subProduct);
                    if($transaction != null){
                        $transaction = $this->mproxy->getLastTransaction($email);
                        $this->mproxy->sendPostedTransNotifications($transaction);
                        if($inputs['service_id'] == Services::Airtime2Cash){
                            $message = 'Your airtime order has been received successfully. It takes an average of 3-5 minutes to complete this transaction';
                        }
                        else{
                            $this->mproxy->handlesServicesAutomation($transaction);
                            $message = $transaction->sub_name . " request has been submitted successfully!";
                        }
                        return json_encode(new JsonResponse("00", $message, $transaction));
                    }
                    else
                        return json_encode(new JsonResponse("-01", "Error processing request. Please try again!"));

                }
            }
            else
                return json_encode(new JsonResponse("-01", "Invalid Amount!"));
        }
        else
            return json_encode(new JsonResponse("-01", "Product is currently unavailable!"));

    }
    public function postWalletTransaction(Request $request){
        $inputs = $request->input();
        $transaction = $this->mproxy->getWalletTransByPayRef($inputs['payment_ref']);
        $amount = $transaction == null ? ($inputs['amount'] / 101.5) : $transaction->amount;
        $msg = "Your wallet account has been successfully credited with N". $amount;
        return json_encode(new JsonResponse("00", $msg, $transaction));
    }
    public function productList(Request $request, $arg){
        $productList = $this->mproxy->getProductsByServiceId($arg);
        return json_encode(new JsonResponse("00", null, $productList));
    }
    public function getBannerList(){
        $bannersList = $this->mproxy->getBannerList(false);
        return json_encode(new JsonResponse("00", null, $bannersList));
    }
    public function walletTransList(Request $request){
        $email = $this->mproxy->getEmailFromJwt($request);
        $walletList = $this->mproxy->getWalletTransHistory($email, false  );
        return json_encode(new JsonResponse("00", null, $walletList));
    }
    public function dataBalList(){
        $dataBalCodeList = $this->mproxy->getDataBalanceCodeList(false);
        return json_encode(new JsonResponse("00", null, $dataBalCodeList));
    }
    public function subProductList(Request $request){
        $email = $this->mproxy->getEmailFromJwt($request);
        $subProdList = $this->mproxy->getAllSubProducts($email);
        return json_encode(new JsonResponse("00", null, $subProdList));
    }
    public function subProductListByProdId(Request $request, $arg){
        $email = $this->mproxy->getEmailFromJwt($request);
        $subProdList = $this->mproxy->getSubProductsByProdId($arg, $email);
        return json_encode(new JsonResponse("00", null, $subProdList));
    }
    public function productTransactionList(Request $request){
        $email = $this->mproxy->getEmailFromJwt($request);
        $transList = $this->mproxy->getProductTransHistory($email);
        return json_encode(new JsonResponse("00", null, $transList));
    }
    public function paymentAcctList(){
        $acctList = $this->mproxy->getAccountList();
        return json_encode(new JsonResponse("00", null, $acctList));
    }
    public function cancelTransaction(Request $request, $arg){
        $this->mproxy->updateTransactionStatus($arg, RequestStatus::Cancelled);
        $trans = $this->mproxy->getTransactionDetailById($arg);
        return json_encode(new JsonResponse("00", "Cancelled Successfully!", $trans));
    }
    public function faqList(){
        $faqList = $this->mproxy->getFaqList();
        return json_encode(new JsonResponse("00", null, $faqList));
    }
    public function requestPayout(Request $request){
        $inputs = $request->input();
        $email = $this->mproxy->getEmailFromJwt($request);
        $userDetail = $this->mproxy->getUserByEmail($email);
        $isVerified = $this->mproxy->isAccountVerified($userDetail);
        if($isVerified){
            $pendingPayout = $this->mproxy->getPendingPayout($email);
            if($pendingPayout == null){
                $accountBal = $this->mproxy->getWalletBalance($email);
                if($inputs['amount'] > 0){
                    if($accountBal >= $inputs['amount']){
                        $inputs['email'] = $email;
                        $this->mproxy->savePayoutRequest($inputs);
                        $payoutRequest = $this->mproxy->getPayoutRequestById($inputs['payout_id']);
                        if($payoutRequest != null){
                            $this->mproxy->handlesWithdrawalAutomation($payoutRequest);
                            return json_encode(new JsonResponse("00", "Withdrawal request of N". number_format($inputs['amount'], 2) . ' has been submitted successfully!', $payoutRequest));
                        }
                        else
                            return json_encode(new JsonResponse("-01", "Payout Request Fail...Try Again!"));
                    }
                    else
                        return json_encode(new JsonResponse('-01', 'Insufficient Balance'));
                }
                else
                    return json_encode(new JsonResponse('-01', 'Invalid Amount!'));
            }
            else
                return json_encode(new JsonResponse('-01', 'You have a pending payout request'));
        }
        else
            return json_encode(new JsonResponse('-01', 'Account not verified'));
    }
    public function bankTransfer(Request $request){
        $inputs = $request->input();
        if($inputs['amount'] > 0){
            $inputs['email'] = $this->mproxy->getEmailFromJwt($request);
            $this->mproxy->saveBankTransfer($inputs);
            $bankTransfer = $this->mproxy->getBankPaymentRef($inputs['payment_ref']);
            if($bankTransfer != null){
                $this->mproxy->sendFundWalletNotification($bankTransfer, PaymentMethod::Bank, 'Fund Wallet - Bank');
                $message = "Your wallet credit request of N ". number_format($bankTransfer->amount, 2). ' is pending approval.';
                return json_encode(new JsonResponse("00", $message, $bankTransfer));
            }
            else
                return json_encode(new JsonResponse("-01", "Error Occurs!"));
        }
        else
            return json_encode(new JsonResponse('-01', 'Invalid Amount'));

    }
    public function fundTransfer(Request $request){
        $inputs = $request->input();
        $inputs['sender_email'] = $this->mproxy->getEmailFromJwt($request);
        if($inputs['amount'] > 0){
            $accountBal = $this->mproxy->getWalletBalance($inputs['sender_email']);
            if($accountBal >= $inputs['amount']){
                $pendingPayout = $this->mproxy->getPendingPayout($inputs['sender_email']);
                if($pendingPayout == null){
                    $this->mproxy->transferFund($inputs);
                    $walletTrans = $this->mproxy->getWalletTransByPayRef($inputs['payment_ref']);
                    if($walletTrans != null){
                        $this->mproxy->sendWalletTransferNotification($walletTrans, $inputs['sender_email'], $inputs['receiver_email']);
                        return json_encode(new JsonResponse("00", "Fund transfer of N". $inputs['amount']. ' is successful!', $walletTrans));
                    }
                    else
                        return json_encode(new JsonResponse('-01', 'Error occurs during transfer'));
                }
                else
                    return json_encode(new JsonResponse('-01', 'You have a pending payout request'));
            }
            else
                return json_encode(new JsonResponse('-01', 'Insufficient Fund'));
        }
        else
            return json_encode(new JsonResponse('-01', 'Invalid Amount'));

    }
    public function verifyAccountNumber(Request $request){
        $inputs = $request->input();
        $response = $this->mproxy->verifyAccountNumber($request->input());
        if($response != null && $response->status && $response->data != null){
            $inputs['acc_name'] = $response->data->account_name;
            return json_encode(new JsonResponse("00", $response->message, $inputs ));
        }
        else
            return json_encode(new JsonResponse("-01", $response->message));

    }
    public function addUserBank(Request $request){
        $inputs = $request->input();
        $bank = $this->mproxy->getUserBankByAcctNo($inputs['acc_no']);
        if($bank == null){
            $inputs['email'] = $this->mproxy->getEmailFromJwt($request);
            $this->mproxy->saveUserBank($inputs);
            $bank = $this->mproxy->getUserBankByAcctNo($inputs['acc_no']);
            $code = $bank == null ? "-01" : "00";
            $message = $bank == null ? "Error Occurs...Try Again!" : "Added Successfully!";
        }
        else{
            if($bank->active == 0){
                $this->mproxy->updateUserBankAccount($bank->acc_no, $inputs);
                $bank->active = strval(ActiveStatus::Active);
                $code = "00"; $message = "Added Successfully!";
            }
            else{
                $code ="-01";  $message = "Account Number already added!";
            }

        }
        return json_encode(new JsonResponse($code, $message, $bank));
    }
    public function getUserBanks(Request $request){
        $email = $this->mproxy->getEmailFromJwt($request);
        $banks = $this->mproxy->getUserBanksByEmail($email);
        return json_encode(new JsonResponse("00", "Bank Account List", $banks));
    }
    public function deleteUserBank(Request $request){
        $inputs = $request->input();
        $this->mproxy->deleteUserBank($inputs['acc_no']);
        $bank = $this->mproxy->getUserBankByAcctNo($inputs['acc_no']);
        return json_encode(new JsonResponse("00", "Deleted Successfully!", $bank));
    }
    public function supportMessages(Request $request){
        $email = $this->mproxy->getEmailFromJwt($request);
        $messages = $this->mproxy->getMySupportMessages($email);
        return json_encode(new JsonResponse("00", "Message List", $messages));
    }
    public function supportReplies(Request $request, $arg){
        $email = $this->mproxy->getEmailFromJwt($request);
        $this->mproxy->updateMessageReadFlag($arg, $email);
        $replies = $this->mproxy->getMessageReplies($arg);
        return json_encode(new JsonResponse("00", "Message Reply List", $replies));
    }
    public function saveSupportReply(Request $request){
        $email = $this->mproxy->getEmailFromJwt($request);
        $input = $request->input();
        $file = $request->hasFile('fileToUpload') ? $request->file('fileToUpload') : null;
        $this->mproxy->saveMessageReply($input, $email, $input['reply_id'], $file );
        $reply = $this->mproxy->getMsgReplyById($input['reply_id']);
        $code = $reply == null ? "-01" : "00";
        $msg = $reply == null ? "Error Occurs!" : "Message Sent Successfully!";
        return json_encode(new JsonResponse($code, $msg, $reply));
    }
    public function appSettingsList(){
        $settings = $this->mproxy->getAppSettings();
        return json_encode(new JsonResponse("00", "Settings List", $settings));
    }
    public function validateNumber(Request $request, $sub_prod_id, $number){
        $response = $this->mproxy->validateBillPayment($sub_prod_id, $number);
        if($response != null){
            if($response['status'] == '200')
                return json_encode(new JsonResponse("00", 'Validation Successful', $response['customerName']));
            else
                return json_encode(new JsonResponse("-01", $response['message']));
        }
        else
            return json_encode(new JsonResponse("-01", "Could not validate"));
    }
    public function validateDiscountCode(Request $request, $sub_prod_id,  $code){
        $email = $this->mproxy->getEmailFromJwt($request);
        $discount = $this->mproxy->validateDiscountCode($email, $sub_prod_id, $code);
        if($discount != null)
            return json_encode(new JsonResponse("00", "Coupon Code", $discount));
        else
            return json_encode(new JsonResponse("-01", "Invalid Coupon code"));
    }
    public function addonsDetail(Request $request, $addon_code){
        $addon = $this->mproxy->getAddonByCode($addon_code);
        if($addon != null)
            return json_encode(new JsonResponse("00", 'Addon Detail', $addon));
        else
            return json_encode(new JsonResponse("-01", 'Could not find an Addon'));
    }
    public function forgotPassword(Request $request, $email){
        $user = $this->mproxy->getUserByEmailPhoneBvn($email);
        if($user != null){
            $this->mproxy->forgotPassword($user);
            return json_encode(new JsonResponse("00", "A password reset link has been sent your mail"));
        }
        else
            return json_encode(new JsonResponse("-01", "Email does not exist!"));
    }
    public function walletBalance(Request $request){
        $email = $this->mproxy->getEmailFromJwt($request);
        $balance = $this->mproxy->getWalletBalance($email);
        return json_encode(new JsonResponse("00", "Wallet Balance!", $balance));
    }
}
