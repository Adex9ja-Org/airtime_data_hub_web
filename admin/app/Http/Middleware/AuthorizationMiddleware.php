<?php

namespace App\Http\Middleware;

use App\Model\Repository;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AuthorizationMiddleware
{
    private $mproxy;

    /**
     * AuthorizationMiddleware constructor.
     */
    public function __construct()
    {
        $this->mproxy = new Repository();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            $user_role = Auth::user()->userRole;
            $uri = $request->route()->uri;
            $pageInfo = $this->mproxy->getPageInfo($user_role, $uri);
            $menuList = $this->mproxy->getPrivilegeMenu($user_role);
            $pendingMessages = $this->mproxy->getUnreadMessages(4);
            $pendingTrans = $this->mproxy->getPendingTransactions(4);
            if($pageInfo == null)
                redirect()->action('ErrorController@permissionDenied')->send();
            else
                View::share(["sideMenuDataList" => $menuList, "pageInfo" => $pageInfo, 'pendingMessages' => $pendingMessages, 'pendingTrans' => $pendingTrans]);
        }
        return $next($request);
    }
}
