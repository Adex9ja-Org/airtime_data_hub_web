<?php

namespace App\Http\Controllers;

use App\Model\Repository;
use App\Model\TableEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetupController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'authorization']);
        parent::__construct();
    }

    public function paymentAccountList(Request $request, $arg = null){
        $accountList = $this->mproxy->getAccountList();
        if($arg != null){
            $accountInfo = $this->mproxy->getBankAcctInfo($arg);
            $bankList = $this->mproxy->getBankList();
        }
        return view('account_list', ['accountList' => $accountList, 'accountInfo' => $accountInfo ?? null, 'bankList' => $bankList ?? null]);
    }

    public function updatePaymentAccount(Request $request, $arg = null){
        return $this->mproxy->updatePaymentAcct($request->input(), $arg);

    }

    public function paymentAccountAdd(){
        $bankList = $this->mproxy->getBankList();
        return view('account_add', ['bankList' => $bankList]);
    }

    public function savePaymentAccount(Request $request){
        return $this->mproxy->savePaymentAcct($request->input());
    }

    public function deactivatePaymentAcct(Request $request, $arg){
        return $this->mproxy->deActivatePaymentAcct($arg);
    }


    public function chargeRateList(Request $request, $arg = null){
        $chargesList = $this->mproxy->getChargesList();
        if($arg != null)
            $charges = $this->mproxy->getChargeRate($arg);
        return view('charges_fee_list', ['chargesList' => $chargesList, 'charges' => $charges ?? null]);
    }

    public function updateChargeRateList(Request $request, $arg = null){
       return $this->mproxy->updateChargesRate($request->input(), $arg);
    }

    public function addChargesRate(){
        return view('charges_fee_add');
    }

    public function saveChargesRate(Request $request){
        return $this->mproxy->saveNewChargeRate($request->input());
    }
    public function deactivateChargesRate(Request $request, $arg){
        return $this->mproxy->deActivateChargeRate($arg);
    }


    public function dataBalanceList(Request $request, $arg = null){
        $dataCodeList = $this->mproxy->getDataBalanceCodeList();
        $networkList = $this->mproxy->getNetworkList();
        if($arg != null)
            $dataCode = $this->mproxy->getDataCode(base64_decode($arg));
        return view('data_bal_list', ['dataCodeList' => $dataCodeList, 'networkList' => $networkList, 'dataCode' => $dataCode ?? null]);
    }

    public function updateDataBalanceList(Request $request, $arg = null){
        return $this->mproxy->updateDataBalanceCode($request->input(), base64_decode($arg));
    }

    public function dataBalanceAdd(){
        $networkList = $this->mproxy->getNetworkList();
        return view('data_bal_add', ['networkList' => $networkList]);
    }

    public function saveDataBalance(Request $request){
        return $this->mproxy->saveNewDataBalCode($request->input());
    }

    public function deactivateDataBalance(Request $request, $arg){
        return $this->mproxy->deactivateDataBal(base64_decode($arg));
    }
}
