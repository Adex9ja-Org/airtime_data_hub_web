<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TestController extends Controller
{
    public function apiTest(Request $request){
        $user = $this->mproxy->getUserByEmail('ainaoluwatosint@gmail.com');
        return $this->mproxy->reserveAccount($user);
    }

    public function verifyPayment(Request $request, $payment_ref){
        $response = $this->mproxy->billPaymentReQuery($payment_ref);
        echo json_encode($response);
    }

    public function checkAutomation(Request $request, $payment_ref){
        $transaction = $this->mproxy->getTransactionDetailById($payment_ref);
        $response = $this->mproxy->handlesServicesAutomation($transaction);
        echo json_encode($response);
    }

    public function clearCache(){
        $exitCode = Artisan::call('optimize:clear');
        return response("All Cache Cleared! " . $exitCode);
    }

    public function optimizeCache(){
        $exitCode = Artisan::call('optimize');
        return response("Optimized Successfully! " . $exitCode);
    }


}
