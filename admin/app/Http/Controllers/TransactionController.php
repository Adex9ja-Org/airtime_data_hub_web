<?php

namespace App\Http\Controllers;

use App\Model\RequestStatus;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'authorization']);
        parent::__construct();
    }

    public function transactionListByStatus(Request $request, $status){
        $approvalStatus = array($status);
        return $this->getTransactionLists($approvalStatus);
    }
    public function queryTodayTransactions(Request $request, $status){
        $data = $this->mproxy->queryTodayTransactions($status);
        return view('transaction_list', ['data' => $data]);
    }
    public function transactionList(){
        $approvalStatus = [RequestStatus::Pending, RequestStatus::Approved, ];
        return $this->getTransactionLists($approvalStatus);
    }
    function failedTransactionList(){
        $approvalStatus = [RequestStatus::Declined, RequestStatus::Failed, RequestStatus::Cancelled, RequestStatus::Insufficient];
        return $this->getTransactionLists($approvalStatus);
    }

    function getTransactionLists($approvalStatus){
        $data = $this->mproxy->getTransactionListByApprovalStatus($approvalStatus);
        return view('transaction_list', ['data' => $data]);
    }
    public function approveTransaction(Request $request, $arg){
        $this->mproxy->updateTransactionStatus($arg, RequestStatus::Approved, null, $this->user->email);
        return back()->with('msg', $this->prepareMessage(true, "Transaction ". RequestStatus::getReqTitle($arg)));
    }
    public function declineTransaction(Request $request, $arg){
        $this->mproxy->updateTransactionStatus($arg, RequestStatus::Declined);
        return back()->with('msg', $this->prepareMessage(true, "Transaction ". RequestStatus::getReqTitle($arg)));
    }
    public function transactionStat(){
        $transTrend = $this->mproxy->getMonthlyTransactionGraphData();
        $approvalStatus = $this->mproxy->getTransApprovalStatusGraphData();
        $topSelling = $this->mproxy->getTransTopSellingGraphData();
        $topBuyers = $this->mproxy->getTransTopBuyerGraphData();
        $channels = $this->mproxy->getTransPaymentChannelGraphData();
        return view('transaction_stat', ['transTrend' => $transTrend, 'approvalStatus' => $approvalStatus, 'topSelling' => $topSelling, 'topBuyers' => $topBuyers, 'channels' => $channels]);
    }
    public function viewTransaction(Request $request, $arg){
        $transaction = $this->mproxy->getTransactionDetailById($arg);
        $userDetail = $this->mproxy->getUserByEmail($transaction->email);
        return view('transaction_view', ['transaction' => $transaction, 'userDetail' => $userDetail]);
    }
    public function userHistory(Request $request, $arg){
        $transList = $this->mproxy->getProductTransHistory(base64_decode($arg));
        return view('transaction_history', ['transList' => $transList]);
    }

    public function queryTransactionsByServiceId(Request $request, $service_id){
        $data = $this->mproxy->queryTransactionsByServiceId($service_id);
        return view('transaction_list', ['data' => $data]);
    }

    public function  productTransactions(Request $request){
        $inputs = $request->input();
        $products = $this->mproxy->getAllProducts();
        if($inputs != null){
            $transactions = $this->mproxy->filterTransactions($inputs);
        }
        return view('transaction_product_list', ['products' => $products, 'data' => $transactions ?? null]);

    }

}
