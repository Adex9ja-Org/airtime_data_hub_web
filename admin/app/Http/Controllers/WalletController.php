<?php

namespace App\Http\Controllers;

use App\Model\PaymentMethod;
use App\Model\Repository;
use App\Model\RequestStatus;
use App\Model\TableEntity;
use App\Model\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'authorization']);
        parent::__construct();
    }

    public function payoutList(){
        $payoutList = $this->mproxy->getPayoutRequests();
        return view('wallet_payout_list', ['payoutList' => $payoutList]);
    }

    public function approvePayout(Request $request, $arg){
        return $this->mproxy->updatePayout($arg, RequestStatus::Approved);
    }

    public function declinePayout(Request $request, $arg){
        return $this->mproxy->updatePayout($arg, RequestStatus::Declined);
    }

    public function walletTransactions(){
        $walletTransList = $this->mproxy->getWalletTrans();
        return view('wallet_trans_list', ['walletTransList' => $walletTransList]);
    }

    public function bankTransferList(){
        $bankTransfers = $this->mproxy->getBankTransfers();
        return view('wallet_bank_transfer_list', ['bankTransfers' => $bankTransfers]);
    }

    public function monifyTransferList(){
        $bankTransfers = $this->mproxy->getMonifyTransList();
        return view('wallet_monify_transfer_list', ['bankTransfers' => $bankTransfers]);
    }

    public function settlementList(){
        $settlements = $this->mproxy->getSettlements();
        return view('wallet_monify_settlement_list', ['settlements' => $settlements]);
    }

    public function approveBankTransferList(Request $request, $arg){
        return $this->mproxy->updateBankTransfer($arg, RequestStatus::Approved);
    }

    public function declineBankTransferList(Request $request, $arg){
        return $this->mproxy->updateBankTransfer($arg, RequestStatus::Cancelled);
    }

    public function addFund(Request $request){
        $input = $request->input();
        return $this->walletOperation($input, TransactionType::CR);
    }

    public function removeFund(Request $request){
        $input = $request->input();
        return $this->walletOperation($input, TransactionType::DR);
    }

    private function walletOperation(array $inputs, string $type)
    {
        $narration = $type == TransactionType::DR ? 'Admin Manual Debit' : 'Admin Manual Credit';
        $transaction = [
            'email' => base64_decode($inputs['email']), 'amount' => $inputs['amount'],
            'narration' => $narration, 'status' => 1,
            'channel_name' => PaymentMethod::Manual, 'trans_type' => $type,
            'payment_ref' => $this->mproxy->getRef(), 'admin_email' => $this->user->email
        ];
        return $this->mproxy->postWalletTransaction($transaction, true);
    }

    public function walletBalanceList(){
        $walletBalance = $this->mproxy->getWalletBalanceList();
        return view('wallet_balance_list', ['walletBalance' => $walletBalance]);
    }

    public function cardPaymentList(){
        $bankTransfers = $this->mproxy->getCardPayments();
        return view('wallet_card_payment_list', ['cardPayments' => $bankTransfers]);
    }

    public function filterWalletTransaction(Request $request){
        $paymentMethods = $this->mproxy->getPaymentChannels();
        $inputs = $request->input();
        if($inputs != null){
            $transactions = $this->mproxy->filterPayments($inputs);
        }
        return view('wallet_transaction_fliter', ['paymentMethods' => $paymentMethods, 'data' => $transactions ?? null]);
    }
}
