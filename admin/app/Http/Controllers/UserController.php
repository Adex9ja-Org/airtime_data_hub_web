<?php

namespace App\Http\Controllers;

use App\Model\TableEntity;
use App\Model\UserRoles;
use App\Model\UserStatus;
use App\UserEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{


    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'authorization'])->except('index', 'validateLogin','logout', 'verifyEmailAddress');
        parent::__construct();
    }
    public function index()
    {
        if(Auth::check())
            return redirect()->intended('dashboard');
        else
            return view('index');
    }
    public function validateLogin(Request $request)
    {
        $user = $this->mproxy->validateUser($request->input());
        if($user != null){
            if($user->active == 1){
                if($user->is_email_verified == 1){
                    Auth::login($user);
                    return redirect()->intended('dashboard');
                }
                else
                    return back()->with('msg', $this->prepareMessage(false, 'Unverified Email Account!'));
            }
            else
                return back()->with('msg', $this->prepareMessage(false, 'Account has been disabled!'));
        }
        else
            return back()->with('msg', $this->prepareMessage(false, 'Invalid user/password'));
    }
    public function dashboard(){
        $reportGraphData = $this->mproxy->getMonthlyTransactionGraphData();
        $data = $this->mproxy->getDashBoardReportData();
        $sales = $this->mproxy->getDailySales();
        $insight = $this->mproxy->getSalesInsight();
        return view('dashboard', ['data' => $data, 'reportGraphData' => $reportGraphData, 'sales' => $sales, 'insight' => $insight]);
    }
    public function profile (){

        return view('profile');
    }
    public function updateProfile (Request $request){
        return $this->mproxy->updateUser($request->input(), $request->input('email'), true);
    }
    public function logout(){
        Auth::logout();
        return redirect()->action('UserController@index');
    }
    public function userList(){
       return $this->getUsersListByRole(UserRoles::user);
    }
    public function agentList(){
        return $this->getUsersListByRole(UserRoles::agent);
    }
    public function adminList(){
        return $this->getUsersListByRole(UserRoles::admin);
    }
    public function bannedUsers(){
        $users = $this->mproxy->getUsersByStatus(UserStatus::banned);
        return view('user_list', ['data' => $users ?? null]);
    }
    private function getUsersListByRole($role){
        $users = $this->mproxy->getUsersListByRole($role);
        return view('user_list', ['data' => $users ?? null]);
    }
    public function deactivateUser(Request $request, $args){
        return $this->mproxy->deactivateUser(base64_decode($args));
    }
    public function activateUser(Request $request, $arg){
        return $this->mproxy->activateUser(base64_decode($arg));
    }
    public function userRoles(){
        $data = $this->mproxy->getUserRoles();
        return view('user_role_list', ['data' => $data ]);
    }
    public function userRolesPages(Request $request, $args){
        $user_roles = $this->mproxy->getUserRoles();
        $user_role = base64_decode($args);
        if($request->has('privileges')){
            $this->mproxy->deleteUserPrivilege($user_role);
            $inputs = $request->input('privileges');
            $this->mproxy->addUserPrivilege($inputs, $user_role);
        }

        $privileges = $this->mproxy->getPrivileges($user_role);

        return view('user_role_list', ['data' => $user_roles, 'privileges' => $privileges ?? null]);
    }
    public function addUser(){
        $user_roles = $this->mproxy->getUserRoles();
        return view('user_add', ['user_roles' => $user_roles] );
    }
    public function viewUser(Request $request, $args){
        $user_roles = $this->mproxy->getUserRoles();
        $user = $this->mproxy->getUserByEmail(base64_decode($args));
        return view('user_add', ['user_roles' => $user_roles, 'user' => $user]);
    }
    public function saveUser(Request $request, $args = null){
        $inputs = $request->input();
        if($args != null)
            return $this->mproxy->updateUser($inputs, $inputs['email'], true, true);
        else
            return $this->mproxy->saveNewUser($inputs, true);
    }
    public function userDetail(Request $request, $arg = null){
        $email = base64_decode($arg);
        $transaction = $this->mproxy->getProductTransHistory($email);
        $userDetail = $this->mproxy->getUserByEmail($email, false);
        $walletList = $this->mproxy->getWalletTransHistory($email, false);
        $banks = $this->mproxy->getUserBanksByEmail($email);
        $balance = $this->mproxy->getWalletBalance($email);
        return view('user_detail', ['transaction' => $transaction, 'userDetail' => $userDetail, 'walletList' => $walletList->toArray(), 'banks' => $banks, 'balance' => $balance]);
    }
    public function verifyEmailAddress(Request $request, $email){
        $email = base64_decode($email);
        $user = $this->mproxy->getUserByEmail($email);
        if($user != null){
            $this->mproxy->updateUserEmailVerification($email);
            return view('email_verified', ['user' => $user]);
        }
        else
            return view("error", ['code' => '600', "msg" => "Verification Denied", "reason" => "Your email account could not be verified at the moment"]);
    }
    public function resendVerificationEmail(Request $request, $email){
        $email = base64_decode($email);
        $user = $this->mproxy->getUserByEmail($email);
        if($user != null){
            $this->mproxy->sendRegMail($user);
            return back()->with('msg', $this->prepareMessage(true, "Verification mail sent!"));
        }
        else
            return view("error", ['code' => '600', "msg" => "Verification Denied", "reason" => "Your email account could not be verified at the moment"]);
    }

}
