<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CallBackController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function paymentNotification(Request $request){
        $inputs = json_decode($request->getContent(), true);
        $response = $this->mproxy->monifyPaymentNotification($inputs);
        return json_encode($response);
    }

    public function settlementNotification(Request $request){
        $response = $this->mproxy->monifySettlementNotification($request);
        return json_encode($response);
    }
}
