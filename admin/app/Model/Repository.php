<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2/8/2020
 * Time: 2:06 PM
 */

namespace App\Model;


use App\Jobs\SendMailJob;
use App\UserEntity;

use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Repository
{
    private $table;
    private $general_push_topic;
    private $cc;
    private $limit;
    private $withdrawal_charge;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        $this->table = new TableEntity();
        $this->general_push_topic = "/topics/general";
        $this->cc = array("info@airtimedatahub.com");
        $this->limit = 1000;
        $this->withdrawal_charge = 50;
    }

    public static function getAccountName($bank_code)
    {
        $bank = DB::selectOne("select * from bank_entity where bank_code = '$bank_code'");
        return $bank->bank_name;
    }

    public function getRef(){
        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
        $date = $now->format("Ymdhisu");
        return substr($date, 0, 17);
    }

    public function getAccountList(){
        return DB::select("SELECT P.*, B.bank_name FROM payment_account_entity as P INNER JOIN bank_entity as B on P.bank_code = B.bank_code");
    }

    public function getBannerList($onlyActive = true)
    {
        if($onlyActive)
            return DB::select("SELECT B.*, S.service_name as banner_name FROM `banner_entity` as B inner join services_entity as S on B.service_id = S.service_id and B.active = 1");
        else
            return DB::select("SELECT B.*, S.service_name as banner_name FROM `banner_entity` as B inner join services_entity as S on B.service_id = S.service_id");
    }

    public function getBannerById($searchValue)
    {
        return $this->table->getSingleItem('banner_entity', 'banner_id', $searchValue);
    }

    public function saveNewBanner( $inputs, $file)
    {
        return $this->table->insertNewEntry('banner_entity', 'banner_id', $inputs , $file, $this->getRef());
    }

    public function getBankList()
    {
        return $this->table->getItemList('bank_entity', 'bank_code', false);
    }

    public function getBankAcctInfo($arg)
    {
        return $this->table->getSingleItem('payment_account_entity', 'acc_no', $arg);
    }

    public function savePaymentAcct($inputs)
    {
        return $this->table->insertNewEntry('payment_account_entity', 'acc_no', $inputs);
    }

    public function getDataBalanceCodeList($onlyActive = true)
    {
        return $this->table->getItemList('data_balance_entity', 'net_code', $onlyActive);
    }

    public function getDataCode($arg)
    {
        return $this->table->getSingleItem('data_balance_entity', 'net_code', $arg);
    }

    public function updateDataBalanceCode($input, $arg)
    {
        return $this->table->updateTable('data_balance_entity', 'net_code', $arg, $input );
    }

    public function updateBanner($inputs, $file,  $arg)
    {
        return $this->table->updateTable('banner_entity', 'banner_id', $arg, $inputs, $file, $this->getRef());
    }

    public function getNetworkList()
    {
        return $this->table->getItemList('network_entity', 'net_name');
    }

    public function saveNewDataBalCode($inputs)
    {
        return $this->table->insertNewEntry('data_balance_entity', 'net_code', $inputs);
    }

    public function deActivatePaymentAcct($arg)
    {
        return $this->table->deactivate('payment_account_entity', 'acc_no', $arg);
    }

    public function updatePaymentAcct($input, $arg)
    {
        return $this->table->updateTable('payment_account_entity', 'acc_no', $arg, $input);
    }

    public function deActivateBanner($arg)
    {
        return $this->table->deactivate('banner_entity', 'banner_id', $arg);
    }

    public function getChargesList()
    {
        return $this->table->getItemList('conversion_rate_entity', 'conversion_id', true);
    }

    public function getChargeRate($arg)
    {
        return $this->table->getSingleItem('conversion_rate_entity', 'conversion_id', $arg);
    }

    public function updateChargesRate($input, $arg)
    {
        return $this->table->updateTable('conversion_rate_entity', 'conversion_id', $arg, $input);
    }

    public function deactivateDataBal($arg)
    {
        return $this->table->deactivate('data_balance_entity', 'net_code', $arg);
    }

    public function deActivateChargeRate($arg)
    {
        return $this->table->deactivate('conversion_rate_entity', 'conversion_id', $arg);
    }

    public function saveNewChargeRate($input)
    {
        $input['conversion_id'] = $this->getRef();
        return $this->table->insertNewEntry('conversion_rate_entity', 'conversion_id', $input);
    }

    public function getUsersList()
    {
        return DB::select("SELECT * from user_entity ORDER by created_at DESC");
    }

    public function deactivateUser($email)
    {
        $data = ['message' => 'User Banned'];
        $user = $this->getUserByEmailPhoneBvn($email);
        $this->sendPushNotification("09", "Account Banned!", "Your account has been banned!", array($user->token), null, $data);
        return $this->table->deactivate('user_entity', 'email', $email);
    }

    public function activateUser($arg)
    {
        return $this->table->activate('user_entity', 'email', $arg);
    }

    public function getUserRoles()
    {
        return $this->table->getItemList('user_role_entity', 'user_role');
    }

    public function deleteUserPrivilege($user_role)
    {
        DB::delete("delete from menu_privilege_entity where user_role = '$user_role'");
    }

    public function addUserPrivilege($inputs, $user_role)
    {
        foreach ($inputs as $input){
            $arr = ['user_role' => $user_role, 'link' => $input];
            $this->table = new TableEntity();
            $this->table->insertNewEntry('menu_privilege_entity', 'id', $arr, null, null, false);
        }
    }

    public function getPrivileges($user_role)
    {
        return DB::select("SELECT ML.title, ML.link, MP.link as privilege from menu_link_entity as ML LEFT join menu_privilege_entity as MP on (ML.link = MP.link and MP.user_role = '$user_role')");
    }

    public function getUserByEmail($email)
    {
        $user = $this->table->getSingleItem('user_entity', 'email', $email);
        $user->virtual_bank_name = 'Sterling Bank Plc';
        return $user ;
    }

    public function saveNewUser($input, bool $fromAdmin = false)
    {
        $data = [
            'active' => ActiveStatus::Active,
            'password' => base64_encode($input['password']),
            'email' => $input['email'],
            'phoneno' => $input['phoneno'],
            'fullname' => $input['fullname'],
            'address' => $input['address'],
            'ref_code' => $input['ref_code'] ?? '',
            'userRole' => $fromAdmin ? $input['userRole'] : UserRoles::user,
        ];
        $referral = $this->table->getSingleItem('referral_entity', 'ref_code', $input['ref_code'] ?? '');
        $input['ref_code'] = $referral == null ? '' : $referral['ref_code'];
        return $this->table->insertNewEntry('user_entity', 'email', $data);
    }

    public function updateUser($input, $email, $encodePassword = false, $adminUpdate = false)
    {
        $old_user = $this->getUserByEmail($email);
        $isVerified = $this->isAccountVerified($old_user);
        $password = $input['password'] ?? $old_user->password;
        $data = [
            'token' => $input['token'] ?? $old_user->token,
            'pin' => $input['pin'] ?? $old_user->pin,
            'gender' => $input['gender'] ?? $old_user->gender,
            'image_url' => $input['image_url'] ?? $old_user->image_url,
            'doc_type' => $input['doc_type'] ?? $old_user->doc_type,
            'doc_url' => $input['doc_url'] ?? $old_user->doc_url,
            'address' => $input['address'] ?? $old_user->address,
            'password' => $encodePassword ? base64_encode($password) : $password,
            'userRole' => $adminUpdate ? $input['userRole'] : $old_user->userRole
        ];


        if(!$isVerified){
            $data['fullname'] = $input['fullname'] ?? $old_user->fullname;
            $data['phoneno'] = $input['phoneno'] ?? $old_user->phoneno;
            $data['dob'] = $input['dob'] ?? $old_user->dob;
            $data['bvn_number'] = $input['bvn_number'] ?? $old_user->bvn_number;
        }

        return $this->table->updateTable('user_entity', 'email', $email, $data);
    }

    public function getMonthlyTransactionGraphData()
    {
        return DB::select("SELECT year(A.created_at) as years, monthname(A.created_at) as months, count(A.created_at) as value from voucher_entity as A  GROUP by concat(year(A.created_at), monthname(A.created_at)) order by A.created_at desc LIMIT 12");
    }

    public function getDashBoardReportData()
    {
        return DB::selectOne("SELECT sum(case when approvalStatus = 1 then 1 else 0 end) as approved, sum( case when approvalStatus = 0 then 1 else 0 end) as pending, (select count(*) from user_entity where userRole = 'User') as users, (select count(*) from sub_product_entity where active = 1) as products, (select count(*) from support_entity) as feedbacks, (SELECT sum(case when trans_type = 'DR' then -amount else amount end) from wallet_entity) as wallet_balance FROM `voucher_entity`");
    }

    public function validateUser($inputs)
    {
        $email = $inputs['email'];
        $password = $inputs['password'];
        $where = ['email' => $email, 'password' => base64_encode($password)];
        $userEntity = new UserEntity();
        return $userEntity->where($where)->first();
    }

    public function getTransactionListByApprovalStatus($approvalStatus)
    {
        return DB::select("SELECT V.*, U.fullname, S.sub_name FROM voucher_entity as V INNER JOIN user_entity as U on V.email = U.email INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id where approvalStatus in (". implode(',', $approvalStatus). ") order by V.created_at desc limit ?", array($this->limit));
    }

    public function getTransApprovalStatusGraphData()
    {
        return DB::select("SELECT (case WHEN approvalStatus = 0 THEN 'Pending' else (case WHEN approvalStatus = 1 THEN 'Approved' else (case WHEN approvalStatus = 2 THEN 'Cancelled' else (case when approvalStatus = 3 then 'Insufficient Balance' else 'Declined' end) end) end) end) as label, count(approvalStatus) as value from voucher_entity GROUP by approvalStatus");
    }

    public function getTransTopSellingGraphData()
    {
        return DB::select("SELECT count(V.sub_prod_id) as value, S.sub_name as label from voucher_entity as V INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id group by V.sub_prod_id order by  count(V.sub_prod_id) desc limit 12");
    }

    public function getTransTopBuyerGraphData()
    {
        return DB::select("SELECT COUNT(V.email) as value, U.fullname as label from voucher_entity as V INNER join user_entity as U on V.email = U.email GROUP by V.email ORDER by  COUNT(V.email) desc LIMIT 12");
    }

    public function getTransPaymentChannelGraphData()
    {
        return DB::select("SELECT count(channel_name) as value, channel_name as label from voucher_entity GROUP by channel_name");
    }

    public function updateTransactionStatus($arg, $status, $auto_ref = '', $approval_officer = '', $token = '', $serial = '')
    {
        if($status == RequestStatus::Approved){
            $params  = [$status, $auto_ref, $approval_officer, $token, $serial, $arg];
            DB::update("update voucher_entity set approvalStatus = ?, auto_ref = ?, approval_officer = ?, cardPin = ?, cardSerialNo = ? where ref = ?", $params );
            $transaction = $this->getTransactionDetailById($arg);
            $user = $this->getUserByEmail($transaction->email);
            $message = "Ref: ". $transaction->ref . " - ". RequestStatus::getReqTitle($status);
            $this->sendPushNotification('01', 'Transaction Status', $message, array($user->token), null, $transaction);
            $this->sendReceiptByMail($transaction, RequestStatus::getReqTitle($status). ' Transaction', array($transaction->email));
        }
        else{
            $params  = [$status, $approval_officer, $token, $arg];
            DB::update("update voucher_entity set approvalStatus = ?, approval_officer = ?, cardPin = ? where ref = ?", $params );
        }

    }

    public function getPageInfo($user_role, $uri)
    {
        return DB::selectOne("SELECT ML.title, ML.menu_cat from menu_privilege_entity as MP INNER join menu_link_entity as ML on MP.link = ML.link where MP.user_role = '$user_role' and ML.link = '/$uri'");
    }

    public function getPrivilegeMenu($user_role)
    {
        return DB::select("select M.*, mce.cat_icon, mce.cat_link, mle.menu_cat, mle.title from menu_category_entity as mce INNER  join menu_link_entity as mle on mce.menu_cat = mle.menu_cat INNER join menu_item_entity as M on mle.link = M.link INNER JOIN menu_privilege_entity as mpe on M.link = mpe.link  where mce.active = 1 and M.active = 1 and mpe.user_role = '$user_role' GROUP by mle.title  order by  mce.order_id,  M.menu_order");
    }

    public function getUnreadMessages($limit)
    {
        return DB::select("SELECT U.fullname, C.message, C.created_at, C.support_id from support_entity as C INNER join user_entity as U on C.email = U.email where read_status = 0 ORDER by C.created_at desc LIMIT $limit");
    }

    public function getPendingTransactions($limit)
    {
        return DB::select("SELECT U.fullname, S.sub_name, V.amount, V.created_at, V.ref from voucher_entity as V INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id INNER join user_entity as U on V.email = U.email WHERE V.approvalStatus = 0 order by V.created_at desc LIMIT $limit");
    }

    public function getTransactionDetailById($arg)
    {
        return DB::selectOne("SELECT U.fullname, V.*, S.sub_name, P.product_name, P.product_icon, P.product_description, C.per_charges from voucher_entity as V INNER join user_entity as U on V.email = U.email INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id INNER join product_entity as P on S.product_id = P.product_id INNER join conversion_rate_entity as C on S.conversion_id = C.conversion_id where V.ref = ?", array($arg));
    }

    public function getMessageList($email)
    {
        return DB::select("SELECT C.*, U.fullname, U.image_url, (SELECT count(*) FROM reply_entity as R where R.support_id = C.support_id and R.email <> ? and R.read_status = 0) as un_read FROM support_entity as C INNER join user_entity as U ON C.email = U.email ORDER by un_read DESC, created_at DESC", array($email));
    }

    public function getMessageDetail($support_id)
    {
        return DB::selectOne("SELECT C.*, U.fullname, U.phoneno, U.userRole, U.image_url FROM support_entity as C INNER join user_entity as U ON C.email = U.email where C.support_id = '$support_id'");
    }

    public function getProductTransHistory($email)
    {
       return DB::select("SELECT V.*, S.sub_name, P.product_name, P.product_icon, P.product_description, P.service_id FROM voucher_entity as V INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id INNER join product_entity as P on S.product_id = P.product_id where V.email = ? order by V.created_at desc", array($email));
    }

    public function updateUserToken($inputs, $email)
    {
        $this->table->updateTable('user_entity', 'email', $email, ["token" => $inputs['token']]);
    }

    public function sendSupportMsg($inputs)
    {
        $data = [
            'support_id' => $inputs['support_id'],
            'email' => $inputs['email'],
            'priority' => $inputs['priority'],
            'message' => $inputs['message'],
        ];
        $this->table->insertNewEntry('support_entity', 'support_id', $data, null, null, false);
    }

    public function postTransaction($inputs, $user, $subProduct)
    {
        $addon_code = $inputs['addon_code'] ?? "";
        switch ($inputs['service_id']){
            case Services::Airtime2Cash:
                $amount = $inputs['amount'];
                $inputs['channel_name'] = PaymentMethod::Bank;
                break;
            default:
                if(!empty($addon_code)) $addon = $this->getAddonByCode($addon_code);
                $user_amount = $user->userRole == UserRoles::agent ? $subProduct->sub_res_price : $subProduct->sub_price;
                $amount = $user_amount == 0 ? $inputs['amount'] : ($user_amount * $subProduct->period);
                $amount = isset($addon) ? ($amount + $addon['addon_price']) : $amount;
                $inputs['channel_name'] = PaymentMethod::Wallet;
                break;
        }

        $transaction = [
            'email' => $user->email,
            'sub_prod_id' => $subProduct->sub_prod_id,
            'cr_acc' => $inputs['cr_acc'] ?? "",
            'dr_acc' => $inputs['dr_acc'] ?? "",
            'amount' => $amount,
            'cardPin' => $inputs['cardPin'] ?? "",
            'approvalStatus' => RequestStatus::Pending,
            'acc_no' => $inputs['acc_no'] ?? "",
            'narration' => $inputs['narration'] ?? "",
            'discount_code' => $inputs['discount_code'] ?? "",
            'mac_address' => $inputs['mac_address'] ?? '',
            'ip_address' => $inputs['ip_address'] ?? '',
            'latitude' => $inputs['latitude'] ?? 0.0,
            'longitude' => $inputs['longitude'] ?? 0.0,
            'addon_code' => $addon_code,
            'channel_name' => $inputs['channel_name'],
            'platform' => $inputs['platform'] ?? 'Android',
        ];
        return $this->table->createRecord('voucher_entity', 'ref', $transaction);
    }

    public function getProductsByServiceId($DATA_SERVICE)
    {
        $where = [['service_id', '=', $DATA_SERVICE]];
        return $this->table->getItemListWithWhere('product_entity', 'product_id', $where);
    }

    public function getSubProductDetail($sub_prod_id)
    {
        return $this->table->getSingleItemWithWhere('sub_product_entity', 'sub_prod_id',  [['sub_prod_id', '=', $sub_prod_id]]);
    }

    public function getWalletTransHistory($arg, $onlyActive = false)
    {
        if ($onlyActive)
            $where = [['email' , '=', $arg], ['status', '=', 1]];
        else
            $where = [['email' , '=', $arg]];
        return $this->table->getItemListWithWhere('wallet_entity', 'id', $where, 'wallet_id');
    }

    public function postWalletTransaction($input, bool $showMessage = false)
    {
        return $this->table->insertNewEntry('wallet_entity', 'id', $input, null, null, $showMessage);
    }

    public function getWalletTransByPayRef($payment_ref)
    {
        $where = [['payment_ref' , '=', $payment_ref]];
        return $this->table->getSingleItemWithWhere('wallet_entity', 'id', $where);
    }

    public function updateUserImage($file, $email)
    {
        $this->table->updateTable('user_entity', 'email', $email, [], $file, $this->getRef());
    }

    public function sendPushNotification(string $code,  $title,  $message, array $registrationIDs = null, string $topic = null, $data = null)
    {
        if(($registrationIDs == null || sizeof($registrationIDs) == 0) && $topic == null)
            return null;
        $url = "https://fcm.googleapis.com/fcm/send";
        $notification = [
            'title' => $title,
            'body' =>  $message,
            'sound' => 'default',
            'badge' => '1'
        ];
        $data_payload = [
            'data' => $data,
            'code' => $code
        ];
        $arrayToSend = [
            'to' => $topic,
            'registration_ids' =>  $registrationIDs,
            'data' => $data_payload,
            'notification' => ($title == null && $message == null) ? null : $notification,
            'priority'=>'high'
        ];
        $client = new Client();
        $result = $client->post( $url, [
            'json'    =>  $arrayToSend,
            'headers' => [  'Authorization' => 'key='. config('app.fcm'),  'Content-Type'  => 'application/json' ],
        ] );
        $response = $result->getBody();
        return json_decode( $response, true );
    }

    public function sendPostedTransNotifications($transaction)
    {
        try {
            $tokens = $this->getAdminTokens();
            $message = "You have a new request from : " . $transaction->email;
            $this->sendPushNotification("03", 'New Request',  $message  , $tokens, null, $transaction);
            $this->sendReceiptByMail($transaction, "Transaction Receipt", array($transaction->email));
            return  'Notification sent';
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    private function sendReceiptByMail($transaction, $subject, array $emails)
    {
       $data = ['trans' => $transaction];
       $this->sendMail('emails.transaction_receipt', $emails, $subject, $this->cc, $data );
    }

    public function getFaqCatList($onlyActive)
    {
        return $this->table->getItemList('faq_cat_entity', 'faq_cat', $onlyActive);
    }

    public function getWalletBalanceList()
    {
        return DB::select("SELECT U.fullname, U.phoneno, U.email, sum(case when W.trans_type = 'DR' THEN -W.amount else W.amount end) as wallet_balance FROM `wallet_entity` as W INNER JOIN user_entity as U on W.email = U.email WHERE W.status = 1 GROUP by W.email order  by W.created_at desc limit ?", array($this->limit));
    }

    public function getPromoProducts()
    {
        return DB::select("SELECT * from product_entity where service_id  <> ?", array("RE_SA_01"));
    }

    public function synchronizeBank()
    {
        $url = "https://api.paystack.co/bank";
        $client = new Client();
        $result = $client->get( $url, ['headers' => [ 'Content-Type' => 'application/json', 'Authorization' => 'Bearer '.config('app.paystack')]]);
        $banks = json_decode( $result->getBody());
        if($banks->status){
            $data = $banks->data;
            foreach ($data as $item){
                $this->table = new TableEntity();
                $bank = $this->table->getSingleItem('bank_entity', 'bank_code', $item->code);
                $inputs = [
                    'bank_code' => $item->code,
                    'bank_name' => $item->name,
                ];
                if($bank == null){
                    $this->table->insertNewEntry('bank_entity', 'bank_code', $inputs);
                }
                else{
                    $this->table->updateTable('bank_entity', 'bank_code', $bank->code, $inputs);
                }
            }
        }
    }

    public function validateDiscountCode($email, $sub_prod_id, $code)
    {
        return DB::selectOne("SELECT A.* from (SELECT D.percentage, D.discount_code, D.usage_number, (SELECT COUNT(discount_code) from voucher_entity where discount_code = D.discount_code and email = ?) as usage_count from discount_entity as D INNER join sub_product_entity as S on S.sub_prod_id = ? inner join product_entity as P on S.product_id = D.product_id WHERE D.discount_code = ? and D.active = 1 and D.expiry_date >= date(now()) AND D.usage_number > 0 GROUP by S.sub_prod_id) as A where A.usage_count < A.usage_number", array($email, $sub_prod_id, $code));
    }

    public function getSalesInsight()
    {
        return DB::select("SELECT SE.service_name, count(V.ref) as counter, sum(V.amount) as total, SE.service_id from voucher_entity as V inner JOIN sub_product_entity as S on V.sub_prod_id = S.sub_prod_id inner join product_entity as P on S.product_id = P.product_id INNER join services_entity as SE on P.service_id = SE.service_id where V.approvalStatus = 1 GROUP by SE.service_id");
    }

    public function queryTransactionsByServiceId($service_id)
    {
        return DB::select("SELECT V.*, U.fullname, S.sub_name FROM voucher_entity as V INNER JOIN user_entity as U on V.email = U.email INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id INNER join product_entity as P on S.product_id = P.product_id where approvalStatus = 1 and P.service_id = ? order by V.created_at desc limit ?", array($service_id, $this->limit));
    }

    public function billPaymentReQuery($payment_ref)
    {
        try {
            $client = new Client();
            $result = $client->post('https://www.api.ringo.ng/api/b2brequery', [
                'json' => [
                    'request_id' => $payment_ref
                ],
                'headers' => [ 'Content-Type' => 'application/json', 'email' => config('app.ringo_email'), 'password' => config('app.ringo_password')],
            ] );
            return json_decode( $result->getBody(), true );
        }catch (\GuzzleHttp\Exception\RequestException $exception){}
    }

    public function getCardPayments()
    {
        return DB::select("SELECT W.*, U.fullname, U.phoneno FROM `wallet_entity` as W INNER join user_entity as U on W.email = U.email WHERE channel_name = 'Paystack' order  by W.created_at DESC limit ?", array($this->limit));
    }

    public function getReferralDetailByCode($ref_code)
    {
        return DB::selectOne("SELECT U.*, R.ref_code from referral_entity as R inner JOIN user_entity as U on R.email = U.email WHERE R.ref_code = ?", array($ref_code));
    }

    public function getAllProducts()
    {
        return $this->table->getItemList('product_entity', 'product_id', true);
    }

    public function filterTransactions($inputs = [])
    {
        return DB::select("SELECT V.*, U.fullname, S.sub_name FROM voucher_entity as V INNER JOIN user_entity as U on V.email = U.email INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id WHERE S.product_id = ? and V.approvalStatus = ? and date(V.created_at) BETWEEN ? and ? order by V.created_at desc", array_values($inputs));
    }

    public function getAccountReserved($email)
    {
        $user = $this->getUserByEmail($email);
        if($user->account_number == null || $user->account_number == '')
            return $this->reserveAccount($user);
        else
            return $user->account_number;
    }

    public function paystackPaymentNotification($inputs)
    {
        if($inputs['event'] == 'charge.success'){
            $paystackData = $inputs['data'];
            $data = [
                'trans_ref' => $paystackData['reference'],
                'amount' => $paystackData['amount'] / 101.5,
                'payment_method' => $paystackData['channel'],
                'gateway' => PaymentMethod::Paystack,
                'narration' => $paystackData['gateway_response'],
                'email' => $paystackData['customer']['email'],
                'status' => $paystackData['status'],
            ];
            $this->table->insertNewEntry('payment_notification_entity', 'trans_ref', $data);
        }
        return new JsonResponse("00", "Notified Successfully");
    }

    public function monifyPaymentNotification($inputs)
    {
        $hash = $this->calculateHashValue($inputs);
        if(hash_equals($inputs['transactionHash'], $hash)){
            $data = [
                'trans_ref' => $inputs['transactionReference'],
                'amount' => $inputs['amountPaid'],
                'payment_method' => $inputs['paymentMethod'],
                'narration' => $inputs['paymentDescription'],
                'email' => $inputs['customer']['email'],
                'status' => $inputs['paymentStatus'],
                'gateway' => PaymentMethod::Monify
            ];
            $this->table->insertNewEntry('payment_notification_entity', 'trans_ref', $data);
            return new JsonResponse("00", "Notified Successfully");
        }
        else
            return new JsonResponse("-01", "Invalid hash");
    }

    public function monifySettlementNotification(Request $request)
    {
        $inputs = json_decode($request->getContent(), true);

        $data = [
            'username' => $request->getUser(),
            'password' => $request->getPassword(),
            'data' => json_encode($inputs)
        ];
        $this->table->insertNewEntry('settlement_log_entity', 'id', ['content' => json_encode($data)]);

        if($request->getUser() == config('app.monify_username') && $request->getPassword() == config('app.monify_password')){
//            $data = [
//                'trans_ref' => $inputs['settlementReference'],
//                'amount' => $inputs['amount'],
//                'acct_num' => $inputs['destinationAccountNumber'],
//                'acct_name' => $inputs['destinationAccountName'],
//                'bank_code' => $inputs['destinationBankName'],
//                'trans_count' => $inputs['transactionsCount'],
//            ];
//

            $data = [
                'trans_ref' => $inputs['settlementReference'],
                'amount' => $inputs['amount'],
                'acct_num' => $inputs['destinationAccountNumber'],
                'acct_name' => $inputs['destinationAccountName'],
                'bank_code' => $inputs['destinationBankName'],
                'trans_count' => $inputs['transactionsCount'],
            ];
            $this->table->insertNewEntry('settlement_notification_entity', 'trans_ref', $data);
            return new JsonResponse("00", "Notified Successfully");
        }
        else
            return new JsonResponse("-01", "Invalid Username & Password");
    }

    public function getMonifyTransList()
    {
        $where = [['gateway', '=', PaymentMethod::Monify]];
        return $this->table->getItemListWithWhere('payment_notification_entity', 'trans_ref', $where);
    }

    public function getSettlements()
    {
        return $this->table->getItemList('settlement_notification_entity', 'trans_ref');
    }

    public function getUsersByStatus(int $status)
    {
        return DB::select("SELECT * from user_entity where active = ? ORDER by created_at DESC limit ?", array($status, $this->limit));
    }

    public function getPaymentChannels()
    {
        return $this->table->getItemList('payment_channel_entity', 'channel_name', true);
    }

    public function filterPayments($inputs)
    {
        return DB::select("SELECT W.*, U.fullname FROM wallet_entity as W INNER JOIN user_entity as U on W.email = U.email WHERE W.channel_name = ? and W.status = ? and date(W.created_at) BETWEEN ? and ? order by W.created_at desc", array_values($inputs));
    }

    public function filterPayouts($inputs)
    {
        return DB::select("SELECT W.*, U.fullname FROM wallet_entity as W INNER JOIN user_entity as U on W.email = U.email WHERE W.narration = 'Payout / Withdrawal' and W.status = ? and date(W.created_at) BETWEEN ? and ? order by W.created_at desc", array_values($inputs));
    }

    public function getAirtime2CashAvailBal($email)
    {
        return DB::selectOne('select  GetA2CWithdrawalBalance(?) as balance', array($email))->balance;
    }

    public function reQueryReservedAccount($user)
    {
        try {
            $client = new Client();
            $result = $client->get( config("app.monify_url") . 'bank-transfer/reserved-accounts/'. $user->phoneno, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer " . $this->getAccessToken(),
                ],
            ]);
            $response = json_decode( $result->getBody(), true );
            if($response['requestSuccessful']){
                $this->updateProvidusAcct($response, $user->email);
            }
            return $response;
        }catch (\GuzzleHttp\Exception\RequestException $exception){
            return null;
        }
    }


    public function getLastTransaction($email)
    {
        return DB::selectOne("SELECT U.fullname, V.*, S.sub_name, P.product_name, P.product_icon, P.product_description, C.per_charges from voucher_entity as V INNER join user_entity as U on V.email = U.email INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id INNER join product_entity as P on S.product_id = P.product_id INNER join conversion_rate_entity as C on S.conversion_id = C.conversion_id where U.email = ? ORDER by V.ref DESC LIMIT 1", array($email));
    }


    private function getAdminEmails()
    {
        $registrationIDs = DB::select("SELECT email from user_entity where userRole = 'Admin' and token <> '' ");
        $arr = [];
        foreach ($registrationIDs as $reg)
            $arr[] = $reg->email;
        return $arr;
    }

    private function getAdminTokens()
    {
        $registrationIDs = DB::select("SELECT token from user_entity where userRole = 'Admin' and token <> '' and email <> 'adex9ja2@gmail.com' ");
        $arr = [];
        foreach ($registrationIDs as $reg)
            $arr[] = $reg->token;
        return $arr;
    }

    public function sendFundWalletNotification($transaction, $payment_method, $subject)
    {
        $user = $this->getUserByEmail($transaction['email']);
        $data = ['trans' => $transaction, 'fullname' => $user['fullname'], 'payment_method' => $payment_method];
        $this->sendMail('emails.fund_wallet_receipt', array($transaction['email']), $subject, $this->cc, $data );
        $message = $transaction['narration']. ' NGN'. $transaction['amount'];
        $this->sendPushNotification('07', 'Fund Wallet', $message , array($user['token']), null, $transaction);
    }

    public function getPushNotifications()
    {
        return $this->table->getItemList('notification_entity');
    }

    public function publishNewPushNotification($inputs, $email)
    {
        $inputs['email'] = $email;
        $this->sendGeneralNotification($inputs);
        return $this->table->insertNewEntry('notification_entity', 'id', $inputs);
    }

    public function resendPushNotification($arg)
    {
        $notification = $this->table->getSingleItem('notification_entity', 'id', $arg);
        $notification['created_at'] = $this->getCurrentDate();
        $this->sendGeneralNotification($notification);
    }

    private function sendGeneralNotification($notification){
        $this->sendPushNotification("04", null, null, null, $this->general_push_topic, $notification);
    }

    public function updateProfileDocument($file, $email, $doc_type)
    {
        $this->table->updateTable('user_entity', 'email', $email, ['doc_type' => $doc_type], $file, $this->getRef(), 'doc_url');
    }

    public function verifyBvn($inputs)
    {
        try {
            $phone = $inputs['bvn_phone'];
            $dob  =  $inputs['bvn_dob'];
            $bvn_number =  $inputs['bvn_number'];
            $bvn = $this->getBvnDetail($bvn_number);
            if($bvn != null)
                return $bvn->bvn_phone == $phone && $bvn->bvn_dob == $dob;
            else{
                $url = "https://api.paystack.co/bank/resolve_bvn/".$bvn_number;
                $client = new Client();
                $result = $client->get( $url, ['headers' => [ 'Content-Type' => 'application/json', 'Authorization' => 'Bearer '.config('app.paystack')]]);
                $bvn = json_decode( $result->getBody());
                if($bvn->status){
                    $bvnInput['bvn_phone'] = $bvn->data->mobile;
                    $bvnInput['bvn_dob'] = $bvn->data->formatted_dob;
                    $bvnInput['bvn_number'] = $bvn_number;
                    $bvnInput['first_name'] = $bvn->data->first_name;
                    $bvnInput['last_name'] = $bvn->data->last_name;
                    $this->table->insertNewEntry('bvn_entity', 'bvn_number', $bvnInput);
                    return $bvnInput['bvn_phone'] == $phone && $bvnInput['bvn_dob'] == $dob;
                }
                return false;
            }

        }catch (\GuzzleHttp\Exception\RequestException $exception){}
    }

    public function sendBulkSms($input)
    {
        $response = $this->sendSMS( $input['message'], $input['phone']);
        $input['response'] = $response;
        $input['email'] = Auth::user()->email;
        $this->table->insertNewEntry('sms_entity', 'id', $input);
        return $response;
    }

    private function sendSMS($message, $phone)
    {
        $url = "http://www.daftsms.com/sms_api.php?username=". config('app.sms_username')."&password=". config('app.sms_password'). "&sender=AirtimeData&dest=". $phone ."&msg=".$message;
        $client = new Client();
        $request = $client->get($url);
        return $request->getBody()->getContents();
    }

    public function getBulkSmsBalance()
    {
        $url = "http://www.daftsms.com/sms_api.php?meg_report=balance&username=". config('app.sms_username') ."&password=". config('app.sms_password');
        $client = new Client();
        $request = $client->get($url);
        return $request->getBody()->getContents();
    }

    public function getBulkSmsStat()
    {
        return DB::selectOne("SELECT (SELECT COUNT(response) from sms_entity where response = '146') as success, (SELECT COUNT(response) from sms_entity where response <> '146') as fail");
    }

    public function getFaqList($onlyActive = false)
    {
        return $this->table->getItemList('faq_entity', 'id', $onlyActive);
    }

    public function getFaqById($arg)
    {
        return $this->table->getSingleItem('faq_entity', 'id', $arg);
    }

    public function deactivateFaq($arg)
    {
        return $this->table->deactivate('faq_entity', 'id', $arg);
    }

    public function updateFaq($inputs, $arg)
    {
        return $this->table->updateTable('faq_entity', 'id', $arg, $inputs);
    }

    public function saveFaq($input)
    {
        return $this->table->insertNewEntry('faq_entity', 'id', $input);
    }

    public function getReferralByEmail($arg)
    {
        return $this->table->getSingleItem('referral_entity', 'email', $arg);
    }

    public function verifyPaymentReference($reference)
    {
        $url = "https://api.paystack.co/transaction/verify/" . rawurlencode($reference);
        $client = new Client();
        $result = $client->get( $url, ['headers' => [ 'Content-Type' => 'application/json', 'Authorization' => 'Bearer '.config('app.paystack')]]);
        return json_decode( $result->getBody());
    }

    public function saveReferralCode($inputs)
    {
        return $this->table->insertNewEntry('referral_entity', 'ref_code', $inputs);
    }

    public function genReferralCode($email, $reference )
    {
        $len = strlen($reference);
        return substr($email, 0, 3). substr($reference, ($len - 4), ($len -1));
    }

    public function getPromoCodes()
    {
        return $this->table->getItemList('discount_entity', 'discount_code', true);
    }

    public function genPromoCode($len)
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < $len; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars)-1)];
        }
        return $res;
    }

    public function savePromoCode($input)
    {
        return $this->table->insertNewEntry('discount_entity', 'discount_code', $input);
    }

    public function getPromoCodeByCode($arg)
    {
        return $this->table->getSingleItem('discount_entity', 'discount_code', $arg);
    }

    public function deactivatePromoCode($arg)
    {
        return $this->table->deactivate('discount_entity', 'discount_code', $arg);
    }

    public function updatePromoCode($input, $arg)
    {
        return $this->table->updateTable('discount_entity', 'discount_code', $arg, $input);
    }

    public function getReferralList()
    {
        return DB::select("SELECT R.*, U.fullname, (SELECT sum(case when U2.userRole = ? THEN 1 ELSE 0 END) from user_entity as U2 where U2.ref_code = R.ref_code) as earned, (SELECT count(*) from user_entity as U3 where U3.ref_code = R.ref_code) as referred from referral_entity as R inner join user_entity as U on R.email = U.email", array(UserRoles::agent));
    }

    public function getReferralEarnings($ref_code)
    {
        return DB::select("SELECT W.amount, W.narration, W.payment_ref, U.fullname, W.created_at from wallet_entity as W INNER join referral_entity as R on W.payment_ref = R.reference INNER JOIN user_entity as U on R.email = U.email where U.ref_code = ?", array($ref_code));
    }

    public function getPendingPayout($email)
    {
        $where = [['email', '=', $email], ['status', '=', 0]];
        return $this->table->getSingleItemWithWhere('payout_entity', 'payout_id', $where);
    }

    public function getWalletBalance($email)
    {
        return DB::selectOne('select  GetWalletBalance(?) as balance', array($email))->balance;
    }

    public function savePayoutRequest($inputs)
    {
        $this->table->insertNewEntry('payout_entity', 'payout_id', $inputs);
    }

    public function getPayoutRequestById($payout_id)
    {
        return  DB::selectOne("SELECT P.*, B.bank_name, UB.acc_name FROM payout_entity as P INNER join user_bank_entity as UB on P.acc_no = UB.acc_no INNER join bank_entity as B on UB.bank_code = B.bank_code where P.payout_id = ?", array($payout_id));
    }

    public function sendPayoutRequestNotification($payoutRequest, $subject = 'Payout Request')
    {
        $tokens = $this->getAdminTokens();
        $user = $this->getUserByEmail($payoutRequest->email);
        $tokens[] = $user['token'];
        $message = "NGN" . $payoutRequest->amount . " payout request " . RequestStatus::getReqTitle($payoutRequest->status);
        $data = ['payoutRequest' => $payoutRequest];
        $this->sendPushNotification("05", 'Payout Request', $message, $tokens, null, $payoutRequest);
        $this->sendMail('emails.withdraw_wallet_receipt', array($user['email']), $subject, $this->cc , $data );
    }

    public function getPayoutRequests()
    {
        return DB::select("SELECT P.*, B.bank_name, UB.acc_name FROM payout_entity as P INNER join user_bank_entity as UB on P.acc_no = UB.acc_no INNER join bank_entity as B on UB.bank_code = B.bank_code ORDER by P.created_at DESC limit ?", array($this->limit));
    }

    public function updatePayout($arg, $value)
    {
        $pendingRequest = $this->getPayoutRequestById($arg);
        $pendingRequest->status = $value;
        $this->sendPayoutRequestNotification($pendingRequest, RequestStatus::getReqTitle($value). ' Payout Request');
        return $this->table->updateTable('payout_entity', 'payout_id', $arg, ['status' => $value]);
    }

    public function getWalletTrans()
    {
        return DB::select("SELECT W.*, V.approval_officer from wallet_entity as W LEFT join voucher_entity as V on W.payment_ref = V.ref order by W.created_at DESC limit ?", array($this->limit));
    }

    public function sendRegMail($user)
    {
        $data = ['fullname' => $user->fullname, 'email' => $user->email];
        $this->sendMail('emails.registration_mail', array($user->email), 'New Registration', [], $data );
    }

    public function saveBankTransfer($inputs)
    {
        $this->table->insertNewEntry('bank_transfer_entity', 'payment_ref', $inputs);
    }

    public function getBankPaymentRef($payment_ref)
    {
        return $this->table->getSingleItemWithWhere('bank_transfer_entity', 'payment_ref', [['payment_ref', '=', $payment_ref]]);
    }

    public function transferFund($inputs)
    {
        $data = [
            'email' => $inputs['receiver_email'],
            'amount' => $inputs['amount'],
            'narration' => 'Fund Transfer',
            'status' => 1,
            'channel_name' => PaymentMethod::Wallet,
            'trans_type' => TransactionType::CR,
            'payment_ref' => $inputs['payment_ref']
        ];
        $this->postWalletTransaction($data);
        $this->table = new TableEntity();
        $data['email'] = $inputs['sender_email'];
        $data['trans_type'] = TransactionType::DR;
        $data['payment_ref'] = 'D'.$inputs['payment_ref'];
        $this->postWalletTransaction($data);
    }

    public function sendWalletTransferNotification($walletTrans, $sender_email, $receiver_email)
    {
        $sender = $this->getUserByEmail($sender_email);
        $receiver  = $this->getUserByEmail($receiver_email);
        $this->sendWalletTransferMail($walletTrans, $sender, $receiver);
        $this->sendWalletTransferPushNotification($walletTrans, $sender, $receiver);

    }

    private function sendWalletTransferMail($walletTrans, $sender, $receiver)
    {
        $receivers = [];
        $receivers[]  = $sender['email'];
        $receivers[]  = $receiver['email'];
        $data = ['trans' => $walletTrans, 'sender' => $sender, 'receiver' => $receiver];
        $this->sendMail('emails.wallet_transfer_receipt', $receivers, 'Fund Transfer', [], $data );
    }

    private function sendWalletTransferPushNotification($walletTrans, $sender, $receiver)
    {
        $tokens = [];
        $tokens[] = $sender['token'];
        $tokens[] = $receiver['token'];
        $message = "Wallet Transaction  NGN". $walletTrans['amount'];
        $this->sendPushNotification("06", 'Wallet Transaction', $message,  $tokens, null, $walletTrans);
    }

    public function getUserByEmailPhoneBvn($phone_email_bvn)
    {
        return DB::selectOne("select * from user_entity where email = '$phone_email_bvn' or phoneno = '$phone_email_bvn' or bvn_number = '$phone_email_bvn'");
    }

    public function getBankTransfers()
    {
        return $this->table->getItemList('bank_transfer_entity', 'payment_ref');
    }

    public function updateBankTransfer($arg, $value)
    {
        $pendingRequest = $this->getBankPaymentRef($arg);
        $pendingRequest['status'] = $value;
        $this->sendFundWalletNotification($pendingRequest, PaymentMethod::Bank, 'Update Wallet Funding');
        return $this->table->updateTable('bank_transfer_entity', 'payment_ref', $arg, ['status' => $value]);
    }

    public function verifyAccountNumber($inputs)
    {
        try {
            $url = "https://api.paystack.co/bank/resolve?account_number=". $inputs['acc_no'] ."&bank_code=". $inputs['bank_code'];
            $client = new Client();
            $result = $client->get( $url, ['headers' => [ 'Content-Type' => 'application/json', 'Authorization' => 'Bearer '.config('app.paystack')]]);
            $acct_resolve = json_decode( $result->getBody());
            return $acct_resolve;
        }catch (\GuzzleHttp\Exception\RequestException $exception){}
    }

    public function generateRef()
    {
        return $this->getRef();
    }

    public function saveUserBank($inputs)
    {
        $data = [
            'active' => ActiveStatus::Active,
            'email' => $inputs['email'],
            'acc_no' => $inputs['acc_no'],
            'bank_code' => $inputs['bank_code'],
            'acc_name' => $inputs['acc_name'],
        ];
        $this->table->insertNewEntry('user_bank_entity', 'acc_no', $data);
    }

    public function getUserBankByAcctNo($acc_no)
    {
        return DB::selectOne("SELECT UB.*, B.bank_name FROM user_bank_entity as UB inner join bank_entity as B on UB.bank_code = B.bank_code where  UB.acc_no = ?", array($acc_no));
    }

    public function getUserBanksByEmail($arg)
    {
        return DB::select("SELECT UB.*, B.bank_name FROM user_bank_entity as UB inner join bank_entity as B on UB.bank_code = B.bank_code where UB.email = ?", array($arg));
    }

    public function deleteUserBank($acc_no)
    {
        return $this->table->deactivate('user_bank_entity', 'acc_no', $acc_no);
    }

    public function updateUserBankAccount($acc_no, $inputs)
    {
        $data = [
            'active' => ActiveStatus::Active,
            'email' => $inputs['email'],
            'bank_code' => $inputs['bank_code'],
            'acc_name' => $inputs['acc_name'],
        ];
        return $this->table->updateTable('user_bank_entity', 'acc_no', $acc_no, $data);
    }

    public function saveNewProduct($input, $file)
    {
        $ref = $this->getRef();
        $input['product_id'] = $ref;
        return $this->table->insertNewEntry('product_entity', 'product_id', $input, $file, $ref, true, 'product_icon');
    }

    public function getProductById($product_id)
    {
        return $this->table->getSingleItem('product_entity', 'product_id', $product_id );
    }

    public function saveNewSubProduct($input)
    {
        $input['sub_prod_id'] = $this->getRef();
        $input['conversion_id'] = "FREE_001";
        return $this->table->insertNewEntry('sub_product_entity', 'sub_prod_id', $input);
    }

    public function updateSubProduct($inputs)
    {
        $sub_prod_id = $inputs['sub_prod_id'];
        return $this->table->updateTable('sub_product_entity', 'sub_prod_id', $sub_prod_id, $inputs);
    }

    public function saveBanks($inputs)
    {
        $count = 0;
        foreach ($inputs as $input){
            $count++;
            $this->table = new TableEntity();
            $bank = [];
            $bank['bank_code'] = $input['Code'];
            $bank['bank_name'] = $input['Name'];
            $this->table->insertNewEntry('bank_entity', 'bank_code', $bank);
        }
        return $count;
    }

    public function updateSubProductStatus($arg, int $status)
    {
        if($status == ActiveStatus::InActive)
            return $this->table->deactivate('sub_product_entity', 'sub_prod_id', $arg );
        else
            return $this->table->activate('sub_product_entity', 'sub_prod_id', $arg );
    }

    public function updateProductStatus($arg, int $DeActivated)
    {
        if($DeActivated == ActiveStatus::InActive)
            return $this->table->deactivate('product_entity', 'product_id', $arg );
        else
            return $this->table->activate('product_entity', 'product_id', $arg );
    }

    private function genAwtToken($user){
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        $payload = json_encode([ 'email' => $user->email, 'fullname' => $user->fullname, 'phoneno' => $user->phoneno ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, config('app.key'), true);

        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public function addJwtToUser($user)
    {
        $token = $this->genAwtToken($user);
        $user->auth_token = $token;
        return $user;
    }

    public function getEmailFromJwt(Request $request)
    {
        $sections = explode('.', $request->header('authorization'));
        $base64UrlPayload = str_replace(['-', '_', ''], ['+', '/', '='],  $sections[1]);
        $user = json_decode(base64_decode($base64UrlPayload));
        return $user->email;
    }

    public function verifyJwtToken($authorization)
    {
        if($authorization != null){
            $sections = explode('.', $authorization);
            $signature = hash_hmac('sha256', $sections[0] . "." . $sections[1], config('app.key'), true);
            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
            return $base64UrlSignature == $sections[2];
        }
        else
            return false;

    }

    public function updateUserEmailVerification(string $email)
    {
        $this->table->updateTable('user_entity', 'email', $email, ['is_email_verified' => 1]);
    }

    public function getMessageReplies($arg)
    {
        return DB::select("SELECT R.*, U.fullname, U.image_url from reply_entity as R INNER join user_entity as U on R.email = U.email where R.support_id = ?", array($arg));
    }

    public function saveMessageReply(array $inputs, $email, $reply_id, $file = null)
    {
        $inputs['reply_id'] = $reply_id;
        $inputs['email'] = $email;
        $this->table->insertNewEntry('reply_entity', 'support_id', $inputs, $file, $inputs['reply_id'], false, 'file_link' );
    }

    public function getSupportMsgById($support_id)
    {
        return $this->table->getSingleItem('support_entity', 'support_id', $support_id);
    }

    public function getMySupportMessages($email)
    {
        return DB::select("SELECT C.*, U.fullname, U.image_url, (SELECT count(*) FROM reply_entity as R where R.support_id = C.support_id and R.email <> ? and R.read_status = 0) as un_read FROM support_entity as C INNER join user_entity as U ON C.email = U.email where C.email = ? ORDER by un_read DESC, created_at DESC", array($email, $email));
    }

    public function updateMessageReadFlag($support_id, $email)
    {
        DB::update("UPDATE reply_entity set read_status = 1 where support_id = ? and email <> ?", array($support_id, $email));
        DB::update("UPDATE support_entity set read_status = 1 where support_id = ?", array($support_id));
    }

    public function getMsgReplyById($reply_id)
    {
        return DB::selectOne("SELECT R.*, U.image_url, U.fullname from reply_entity as R INNER JOIN user_entity as U on R.email = U.email where R.reply_id = ?", array($reply_id));
    }

    public function updateThreadStatus($support_id, $ticket_status)
    {
        return $this->table->updateTable('support_entity', 'support_id', $support_id, ['ticket_status' => $ticket_status]);
    }

    public function sendSupportReplyPushNotification(string $reply_id)
    {
        $user = DB::selectOne("SELECT U.token, R.reply_message from reply_entity as R inner join support_entity as S on R.support_id = S.support_id inner join user_entity as U on S.email = U.email where R.reply_id = ?", array($reply_id));
        $reply = $this->getMsgReplyById($reply_id);
        $this->sendPushNotification("08", 'New Reply', $reply->reply_message, array($user->token), null, $reply);
    }

    public function getServices()
    {
        return $this->table->getItemListWithWhere('services_entity', 'services_id', null);
    }

    public function getAppSettings()
    {
        return $this->table->getItemList('settings_entity', 'settings_id');
    }

    public function updateAppSettings($inputs)
    {
        foreach ($inputs as $key => $value){
            $data = ['settings_id' => $key, 'settings_desc' => $value];
            $this->table->updateTable('settings_entity', 'settings_id', $key, $data);
        }

    }

    public function sendMailToSpecifiedUsers($inputs)
    {
        $to =[]; $cc =[];

        if(isset($inputs['to'])){
            $str = implode(',', $inputs['to']);
            $to_emails = DB::select("SELECT email from user_entity where userRole in (?)", array($str));
            foreach ($to_emails as $item){
                if (filter_var($item->email, FILTER_VALIDATE_EMAIL))
                    $to[] = $item->email;
            }
        }
        if(isset($inputs['cc'])){
            $str = implode(',', $inputs['cc']);
            $cc_emails = DB::select("SELECT email from user_entity where userRole in (?)", array($str));
            foreach ($cc_emails as $item){
                if (filter_var($item->email, FILTER_VALIDATE_EMAIL))
                    $cc[] = $item->email;
            }
        }
        $data = ['rawMessage' => $inputs['message']];
        $this->sendMail('emails.broadcast_mail', $to, $inputs['subject'], $cc, $data);
        $inputs['msg_to'] = implode(',', $inputs['to']);
        $inputs['msg_cc'] = isset($inputs['cc']) ? implode(',', $inputs['cc']) : '';
        unset($inputs['to']);
        unset($inputs['cc']);
        $inputs['email'] = Auth::user()->email;
        return $this->table->insertNewEntry('mail_entity', 'id', $inputs);
    }

    private function sendMail($view, $to, $subject, $cc, $data)
    {
       SendMailJob::dispatch($view, $to, $subject, $cc, $data);
    }

    public function getAllSentMails()
    {
        return $this->table->getItemList('mail_entity',  'id', true);
    }

    public function getDraftMails()
    {
        return $this->table->getItemListWithWhere('mail_entity',  'id', ['active' => ActiveStatus::InActive]);
    }

    public function saveMailDraft($inputs)
    {
        unset($inputs['to']);
        unset($inputs['cc']);
        $inputs['active'] = ActiveStatus::InActive;
        $inputs['email'] = Auth::user()->email;
        return $this->table->insertNewEntry('mail_entity', 'id', $inputs);
    }

    public function getMailById($arg)
    {
        return DB::selectOne("SELECT * FROM `mail_entity` as M INNER join user_entity as U on M.email = U.email where M.id = ?", array($arg));
    }

    public function synchronizeData()
    {
        $client = new Client();
        $result = $client->get( config('app.sme_plug_url'). 'data/plans', [
            'headers' => [ 'Content-Type' => 'application/json', 'Authorization' => 'Bearer '. config('app.sme_plug_key')]
        ]);
        $response = $result->getBody();
        $response =  json_decode( $response, true );
        if($response['status']){
            $product = ["1", "2", "3", "4"];
            foreach ($product as $index){
                foreach ($response['data'][$index] as $item){
                    $sub_prod = $this->table->getSingleItemWithWhere('sub_product_entity', 'sub_prod_id', ['auto_sub_prod_id' => $item['id']]);
                    if($sub_prod == null){
                        $productResult = $this->getProductByAutomationId($index);
                        if($productResult != null){
                            $entry = [
                                'auto_sub_prod_id' => $item['id'],
                                'sub_prod_id' => $this->getRef(),
                                'sub_price' => $item['price'],
                                'sub_res_price' => $item['price'],
                                'product_id' => $productResult->product_id,
                                'conversion_id' => 'FREE_001',
                                'active' => 1,
                                'sub_name' => $item['name'],
                                'dialog_id' => 'BEN_LAYOUT'
                            ];
                            $this->table = new TableEntity();
                            $this->table->insertNewEntry('sub_product_entity', 'sub_prod_id', $entry);
                        }
                    }
                }
            }
        }
    }

    private function getProductByAutomationId($index)
    {
        return $this->table->getSingleItemWithWhere('product_entity', 'product_id', ['auto_prod_id' => $index]);
    }

    private function getCurrentDate()
    {
        $now = date("Y-m-d H:i:s");
        return date("Y-m-d H:i:s", strtotime('+1 hours', strtotime($now)));
    }

    public function handlesWithdrawalAutomation($payoutRequest)
    {
        try {
            $bank = $this->getUserBankByAcctNo($payoutRequest->acc_no);
            $client = new Client();
            $api_key = $this->getMonifyBasicAuth();
            $result = $client->post( config('app.monify_url'). 'disbursements/single', [
                'json' =>  [
                    'reference' => $payoutRequest->payout_id,
                    'bankCode' => $bank->bank_code,
                    'accountNumber' => $payoutRequest->acc_no,
                    'currency' => "NGN",
                    'walletId' => config('app.monify_wallet_id'),
                    'amount' => ( $payoutRequest->amount - $this->withdrawal_charge ),
                    'narration' => config('app.name'). ' Payout',
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Basic " . $api_key
                ],
            ] );
            $response = json_decode( $result->getBody(), true );
            $this->withdrawalResponse($response, $payoutRequest->payout_id);

        }catch (\GuzzleHttp\Exception\RequestException $exception){
            $inputs = [
                'payment_ref' => $payoutRequest->payout_id,
                'response' => $exception->getMessage()
            ];
            $this->table = new TableEntity();
            $this->table->insertNewEntry('monify_payment_log_entity', 'payment_ref', $inputs);
        }
    }

    private function withdrawalResponse($response, $payout_id)
    {
        $inputs = [
            'payment_ref' => $payout_id,
            'response' => json_encode($response)
            ];
        $this->table->insertNewEntry('monify_payment_log_entity', 'payment_ref', $inputs);
        $this->updatePayout($payout_id, RequestStatus::Approved);
    }

    public function validateBillPayment($sub_prod_id, $number)
    {
        try {
            $product = $this->getProductBySubId($sub_prod_id);
            if($product->auto_prod_id != ''){
                if($product->auto_prod_id == 'ELECT')
                    $json = [
                        'serviceCode' => 'V-ELECT', 'disco' => $product->auto_type,
                        'type' => $product->auto_sub_prod_id,  'meterNo' => $number
                    ];
                elseif ($product->auto_prod_id == 'TV' || $product->auto_prod_id == 'Internet')
                    $json = [
                        'serviceCode' => 'V-'. $product->auto_prod_id, 'type' => $product->auto_type,
                        'smartCardNo' => $number, 'account' => $number
                    ];
                else
                    $json = [
                        'serviceCode' => "SRV",
                        'account' => $number
                    ];
                $client = new Client();
                $result = $client->post( config('app.ringo_url'), [
                    'json' =>  $json,
                    'headers' => [ 'Content-Type' => 'application/json', 'email' => config('app.ringo_email'), 'password' => config('app.ringo_password')],
                ] );
                return  json_decode( $result->getBody(), true );
            }
            else
                return null;
        }catch (\GuzzleHttp\Exception\RequestException $exception){
            return null;
        }

    }

    public function handlesServicesAutomation($transaction)
    {
        $autoInfo = $this->getAutomationInfo($transaction->ref);
        if($autoInfo != null){
            $processed = $this->getAutoProcessingLog($transaction->ref);
            if($processed == null){
                $this->insertAutoProcessing($transaction);
                $accountBal = $this->getWalletBalance($transaction->email);
                if($accountBal >= $transaction->amount){
                    switch ($autoInfo->service_id){
                        case Services::Data_Purchase:
                            $this->dataPurchaseAutomation($transaction, $autoInfo);
                            break;
                        case Services::AirtimePurchase:
                            $this->airtimeAutomation($transaction, $autoInfo);
                            break;
                        case Services::Bill_Payment:
                            $this->billPaymentAutomation($transaction, $autoInfo);
                            break;
                    }
                }
                else
                    $this->updateTransactionStatus($transaction->ref, RequestStatus::Insufficient);
            }

        }
    }

    private function getAutomationInfo($ref)
    {
        return DB::selectOne("SELECT P.service_id,  S.auto_sub_prod_id, P.auto_prod_id, S.sub_price, P.auto_type, S.sub_name, S.period from voucher_entity as V INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id inner join product_entity as P on S.product_id = P.product_id where V.ref = ?", array($ref));
    }

    private function dataPurchaseAutomation($transaction, $autoInfo)
    {
        try {
            if($autoInfo->auto_sub_prod_id != ''){
                $client = new Client();
                $result = $client->post( config('app.sme_plug_url'). 'data/purchase', [
                    'json' =>  [
                        'network_id' => $autoInfo->auto_prod_id,
                        'plan_id' => $autoInfo->auto_sub_prod_id,
                        'phone' => $transaction->cr_acc,
                    ],
                    'headers' => [ 'Content-Type' => 'application/json', 'Authorization' => 'Bearer '. config('app.sme_plug_key')],
                ] );
                $response =  json_decode( $result->getBody(), true );
                $this->updateSmePlugAutoResponse($response, $transaction);
            }
        }catch (\GuzzleHttp\Exception\RequestException $exception){
            $response = ['status' => false, 'msg' => $exception->getMessage()];
            $this->updateSmePlugAutoResponse($response, $transaction);
        }
    }

    private function airtimeAutomation($transaction, $autoInfo)
    {
        try {
            if($autoInfo->auto_prod_id != ''){
                $splitted = explode(' ', $autoInfo->sub_name);
                $amount = $autoInfo->sub_price > 0 ? $splitted[1]  : $transaction->amount;
                $client = new Client();
                $result = $client->post( config('app.sme_plug_url'). 'vtu', [
                    'json' =>  [
                        'network_id' => $autoInfo->auto_prod_id,
                        'amount' => $amount,
                        'phone_number' => $transaction->cr_acc,
                    ],
                    'headers' => [ 'Content-Type' => 'application/json', 'Authorization' => 'Bearer '. config('app.sme_plug_key')],
                ] );
                $response =  json_decode( $result->getBody(), true );
                $this->updateSmePlugAutoResponse($response, $transaction);
            }
        }catch (\GuzzleHttp\Exception\RequestException $exception){
            $response = ['status' => false, 'msg' => $exception->getMessage()];
            $this->updateSmePlugAutoResponse($response, $transaction);
        }

    }

    private function billPaymentAutomation($transaction, $autoInfo)
    {
        try {
            if($autoInfo->auto_sub_prod_id != ''){
                $user = $this->getUserByEmailPhoneBvn($transaction->email);

                switch ($autoInfo->auto_prod_id){
                    case 'ELECT':
                        $json =  [
                            'serviceCode' => 'P-ELECT',
                            'disco' => $autoInfo->auto_type,
                            'meterNo' => $transaction->cr_acc,
                            'type' => $autoInfo->auto_sub_prod_id,
                            'amount' => (int)$transaction->amount,
                            'phonenumber' => $user->phoneno,
                            'request_id' => $transaction->ref
                        ];
                        $response = $this->makeBillPaymentApiCall($json);
                        $token = $response['token'] ?? "";
                        break;
                    case 'TV':
                        $json =  [
                            'serviceCode' => 'P-TV',
                            'type' => $autoInfo->auto_type,
                            'smartCardNo' => $transaction->cr_acc,
                            'name' => $autoInfo->sub_name,
                            'code' => $autoInfo->auto_sub_prod_id,
                            'period' => $autoInfo->period,
                            'request_id' => $transaction->ref,
                            'hasAddon' => isset($transaction->addon_code) && $transaction->addon_code != '',
                            'price' => $transaction->amount,
                            "addondetails" =>  [
                                        "name" => "HDPVR/XtraView",
                                        "addoncode" => "HDPVRE36"
                                    ]
                        ];
                        $response = $this->makeBillPaymentApiCall($json);
                        break;
                    case 'Internet':
                        $json =  [
                            'serviceCode' => 'P-Internet',
                            'account' => $transaction->cr_acc,
                            'request_id' => $transaction->ref,
                            'amount' => (int)$transaction->amount,
                            'pinNo' => $autoInfo->auto_sub_prod_id,
                            'type' => $autoInfo->auto_type
                        ];
                        $response = $this->makeBillPaymentApiCall($json);
                        break;
                    default:
                        $json =  [
                            'serviceCode' => $autoInfo->auto_prod_id,
                            'account' => $transaction->cr_acc,
                            'request_id' => $transaction->ref,
                            'amount' => (int)$transaction->amount,
                            'pinNo' => $autoInfo->auto_sub_prod_id,
                            'type' => $autoInfo->auto_type
                        ];
                        $response = $this->makeBillPaymentApiCall($json);
                        break;
                }
                if(isset($response['pin_based']) && $response['pin_based']){
                    $token = isset($response['pin']) && sizeof($response['pin']) > 0 ? $response['pin'][0]['pin'] : "";
                    $serial = isset($response['serial']) && sizeof($response['pin']) > 0 ? $response['pin'][0]['serial'] : "";
                }

                $this->updateRingoAutoResponse($response['status'], $response['message'], $transaction, $token ?? "", $response['transref'] ?? "", $serial ?? "", $json ?? []);
            }
        }catch (\GuzzleHttp\Exception\RequestException $exception){
            $this->updateRingoAutoResponse("404", $exception->getMessage(), $transaction);
        }
    }

    private function makeBillPaymentApiCall($json){
        try {
            $client = new Client();
            $result = $client->post( config('app.ringo_url'), [
                'json' => $json,
                'headers' => [ 'Content-Type' => 'application/json', 'email' => config('app.ringo_email'), 'password' => config('app.ringo_password')],
            ] );
            return json_decode( $result->getBody(), true );
        }catch (\GuzzleHttp\Exception\RequestException $exception){}
    }

    private function updateSmePlugAutoResponse($response, $transaction)
    {
        if($response['status']){
            $data = $response['data'];
            $this->updateTransactionStatus($transaction->ref, RequestStatus::Approved, $data['reference']);
            $this->updateAutoProcessing($transaction->ref, $data['msg'], $response['status'], $data['reference']);
        }
        else
            $this->updateAutoProcessing($transaction->ref,  $response['msg'], $response['status']);
    }

    private function updateRingoAutoResponse($status, $message, $transaction, $token = '', $reference = '', $serial = '', $raw = [])
    {
        if($status == "200")
            $this->updateTransactionStatus($transaction->ref, RequestStatus::Approved, $transaction->ref, null, $token, $serial);
        else
            $this->updateTransactionStatus($transaction->ref, RequestStatus::Failed, $transaction->ref);
        $this->updateAutoProcessing($transaction->ref,  $message, $status, $reference, json_encode($raw));

    }

    public function getDailySales()
    {
        return DB::selectOne("SELECT count(*) as sales_count, sum(amount) as sales_amount from voucher_entity where date(created_at) = date(date_add(now(), INTERVAL 5 hour)) and approvalStatus = 1");
    }

    private function getProductBySubId($sub_prod_id)
    {
        return DB::selectOne("SELECT P.*, S.auto_sub_prod_id FROM sub_product_entity as S inner join product_entity as P on S.product_id = P.product_id where S.sub_prod_id = ?", array($sub_prod_id));
    }

    public function getUsersListByRole($role)
    {
        return DB::select("SELECT * from user_entity where userRole = ? ORDER by created_at DESC limit ?", array($role, $this->limit));
    }

    public function filterTransactionByTransId($term)
    {
        return DB::select("SELECT V.*, U.fullname, S.sub_name FROM voucher_entity as V INNER JOIN user_entity as U on V.email = U.email INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id where ref like '%$term%' order by V.created_at desc");
    }

    public function filterUsers($term)
    {
        return DB::select("SELECT * from user_entity where email like '%$term%' or fullname like '%$term%' or phoneno like '%$term%'");
    }

    public function updateAppSplashScreen($file)
    {
        $this->table->updateTable('settings_entity', 'settings_id', 'app_splash', [], $file, $this->getRef(), 'settings_desc');
    }

    public function getAllSubProducts($email)
    {
        return DB::select("SELECT S.*, S.sub_price as sub_non_res_price, C.per_charges, (case when U.userRole = 'Agent' and S.sub_res_price > 0.0 THEN S.sub_res_price else S.sub_price end) as sub_price from sub_product_entity as S INNER join conversion_rate_entity as C on S.conversion_id = C.conversion_id left join user_entity as U on U.email = ?", array($email));
    }

    public function getSubProductsByProdId($product_id, $email = null)
    {
        return DB::select("SELECT S.*, S.sub_price as sub_non_res_price, C.per_charges, (case when U.userRole = 'Agent' and S.sub_res_price > 0.0 THEN S.sub_res_price else S.sub_price end) as sub_price from sub_product_entity as S INNER join conversion_rate_entity as C on S.conversion_id = C.conversion_id left join user_entity as U on U.email = ? where S.product_id = ?", array($email, $product_id));
    }

    public function queryTodayTransactions($status)
    {
        return DB::select("SELECT V.*, U.fullname, S.sub_name FROM voucher_entity as V INNER JOIN user_entity as U on V.email = U.email INNER join sub_product_entity as S on V.sub_prod_id = S.sub_prod_id where approvalStatus = ? and date(V.created_at) = date(date_add(now(), INTERVAL 5 hour)) order by V.created_at desc limit ?", array($status, $this->limit));
    }

    private function insertAutoProcessing($transaction)
    {
        $this->table = new TableEntity();
        $input = [
            'ref' => $transaction->ref,
            'created_at' => $transaction->created_at
        ];
        $this->table->insertNewEntry('log_entity', 'ref', $input);
    }

    private function getAutoProcessingLog($ref)
    {
        $this->table = new TableEntity();
        $inputs = [['ref', '=', $ref]];
        return $this->table->getSingleItemWithWhere('log_entity', 'ref', $inputs);
    }

    private function updateAutoProcessing($trans_ref, $msg, $status, $reference = '', $raw = '')
    {
        $v_status = $status ? 'success' : 'fail';
        DB::update("UPDATE log_entity set msg = ?, status = ?, reference = ?, raw_req = ? WHERE ref = ?", array($msg, $v_status, $reference, $raw, $trans_ref));
    }

    public function getAddonByCode($addon_code)
    {
        return $this->table->getSingleItem('addons_entity', 'addon_code', $addon_code);
    }

    public function getBvnDetail($bvn_number)
    {
        return $this->table->getSingleItemWithWhere('bvn_entity', 'bvn_number', [['bvn_number', '=', $bvn_number]]);
    }

    private function getAccessToken(){
        try {
            $api_key = $this->getMonifyBasicAuth();
            $client = new Client();
            $result = $client->post( config("app.monify_url") . 'auth/login', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Basic " . $api_key
                ],
            ]);
            $response = json_decode( $result->getBody(), true );
            return $response['responseBody']['accessToken'];
        }catch (\GuzzleHttp\Exception\RequestException $exception){
            return null;
        }
    }

    public function reserveAccount($user){
        try {
            $client = new Client();
            $result = $client->post( config("app.monify_url") . 'bank-transfer/reserved-accounts', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer " . $this->getAccessToken(),
                ],
                'json' => [
                    "accountReference" => $user->phoneno,
                    "accountName" => $user->fullname,
                    "currencyCode" => "NGN",
                    "contractCode" => config('app.monify_contract_code'),
                    "customerEmail" => $user->email,
                    "customerName" => $user->fullname,
                    "customerBvn" => $user->bvn_number ?? ""
                ]
            ]);
            $response = json_decode( $result->getBody(), true );
            if($response['requestSuccessful']){
                $accountNumber = $response['responseBody']['accountNumber'];
                $this->updateProvidusAcct($response, $user->email);
                return $accountNumber;
            }
            else
                return  null;
        }catch (\GuzzleHttp\Exception\RequestException $exception){
            return null;
        }
    }

    private function calculateHashValue($inputs)
    {
        $params = config('app.monify_secret_key'). '|' . $inputs['paymentReference']. '|' .
                  $inputs['amountPaid'] . '|' . $inputs['paidOn'] . '|' . $inputs['transactionReference'];
        return hash("SHA512", $params);
    }

    private function getMonifyBasicAuth()
    {
        return base64_encode( config("app.monify_api_key"). ':' . config("app.monify_secret_key"));
    }

    public function forgotPassword($user)
    {
        $data = DB::selectOne("select count(*) as counter, concat(conv(floor(rand() * 99999999999999), 20, 36),conv(floor(rand() * 99999999999999), 20, 36),conv(floor(rand() * 99999999999999), 20, 36)) as otp, otp_code from otp_entity where email = ? and timestampdiff(HOUR, gen_date, now())<= 24 and active = 1", array($user->email));
        $otp_code = $data->counter == 0 ? $data->otp : $data->otp_code;
        if($data->counter == 0){//Insert new otp if none exist / already expired
            DB::insert("INSERT INTO otp_entity(email, otp_code) VALUES(?, ?)", array($user->email, $otp_code));
            $data = [
                'fullname' => $user->fullname,
                'email' => base64_encode($user->email),
                'token' => base64_encode($otp_code),
            ];
            $this->sendMail('emails.forgot_password', $user->email, 'Account Recovery', [], $data);
        }
    }

    private function updateProvidusAcct($response, $email)
    {
        $accountNumber = $response['responseBody']['accountNumber'];
        $bankCode = $response['responseBody']['bankCode'];
        $data = [
            'account_number' => $accountNumber,
            'bank_code' => $bankCode
        ];
        return $this->table->updateTable('user_entity', 'email', $email, $data);
    }

    public function isAccountVerified($old_user)
    {
        return isset($old_user->bvn_number) && $old_user->bvn_number != null && $old_user->bvn_number != "";
    }
}
