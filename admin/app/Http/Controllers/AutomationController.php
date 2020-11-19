<?php

namespace App\Http\Controllers;

use App\Model\JsonResponse;
use App\Model\PaymentMethod;
use App\Model\Repository;
use App\Model\RequestStatus;
use App\Model\TableEntity;
use App\Model\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutomationController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'authorization']);
        parent::__construct();
    }

    public function synchronize(){
        $this->mproxy->synchronizeData();
        $this->mproxy->synchronizeBank();
        return back()->with('msg', $this->prepareMessage(true, "Synchronization completed successfully!"));
    }


}
