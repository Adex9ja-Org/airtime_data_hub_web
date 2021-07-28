<?php

namespace App\Http\Controllers;

use App\Model\JsonResponse;

class MobileApiErrorController extends Controller
{

    public function permissionDenied (){
        return json_encode(new JsonResponse("100", "Authentication Fail", null));
    }

    public function tokenExpired(){
        return json_encode(new JsonResponse("101", "Token Expired!", null));
    }

}
