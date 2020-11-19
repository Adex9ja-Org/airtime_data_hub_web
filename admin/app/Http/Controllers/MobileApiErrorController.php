<?php

namespace App\Http\Controllers;

use App\Model\JsonResponse;

class MobileApiErrorController extends Controller
{

    public function permissionDenied (){
        return json_encode(new JsonResponse("-01", "Authentication Fail", null));
    }

}
