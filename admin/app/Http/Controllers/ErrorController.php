<?php

namespace App\Http\Controllers;

use App\Model\JsonResponse;
use App\Model\TableEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ErrorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'validateLogin','logout');
    }

    public function permissionDenied(){
        return view("error", ['code' => '550', "msg" => "Permission Denied", "reason" => "You do not have required permission to view this page"]);
    }


}
