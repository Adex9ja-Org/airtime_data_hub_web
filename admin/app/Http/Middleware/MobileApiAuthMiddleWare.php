<?php

namespace App\Http\Middleware;

use App\Model\Repository;
use Closure;
use Illuminate\Http\Request;

class MobileApiAuthMiddleWare
{

    private $sha1 = ['5B:3E:7C:47:A3:62:AF:B4:31:45:C9:04:D9:CA:28:F9:0E:CA:93:8B', '7E:22:F2:7C:38:99:C4:B7:62:89:5B:FE:BA:AE:5E:56:1D:0E:6A:AD'];
    private $mproxy;
    /**
     * MobileApiAuthMiddleWare constructor.
     */
    public function __construct()
    {
        $this->mproxy = new Repository();
    }

    public function handle(Request $request, Closure $next)
    {
        if(!in_array($request->header('sha1'), $this->sha1)
            || !$this->mproxy->verifyJwtToken($request->header('authorization'))){
            redirect()->action('MobileApiErrorController@permissionDenied')->send();
        }
        else{
            $email = $this->mproxy->getEmailFromJwt($request);
            $user = $this->mproxy->getUserByEmail($email);
            if($user->is_email_verified != 1 || $user->active != 1){
                redirect()->action('MobileApiErrorController@permissionDenied')->send();
            }
        }

        return $next($request);
    }
}
