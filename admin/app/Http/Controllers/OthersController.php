<?php

namespace App\Http\Controllers;

use App\Model\TableEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OthersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'authorization']);
        parent::__construct();
    }

    public function bannerList(Request $request, $arg = null){
        $bannerList = $this->mproxy->getBannerList();
        $banner = $this->mproxy->getBannerById($arg);
        $services = $this->mproxy->getServices();
        return view('banner_list', ['bannerList' => $bannerList, 'banner' => $banner ?? null, 'services' => $services]);
    }

    public function updateBanner(Request $request, $arg = null){
        return $this->mproxy->updateBanner($request->input(), $request->file('fileUpload'), $arg);
    }

    public function deactivateBanner(Request $request, $arg = null){
        return $this->mproxy->deActivateBanner($arg);

    }

    public function bannerAdd(){
        $services = $this->mproxy->getServices();
        return view('banner_add', ['services' => $services]);
    }

    public function saveNewBanner(Request $request){
        return $this->mproxy->saveNewBanner($request->input(), $request->file('imageUpload'));
    }

    public function faqList(Request $request, $arg = 0){
        $faqList = $this->mproxy->getFaqList(true);
        $faqCatList = $this->mproxy->getFaqCatList(true);
        $faq = $this->mproxy->getFaqById($arg);
        return view('faq_list', ['faqList' => $faqList, 'faq' => $faq, 'faqCatList' => $faqCatList]);
    }

    public function deactivateFaq(Request $request, $arg = null){
        return $this->mproxy->deactivateFaq($arg);
    }

    public function updateFaq(Request $request, $arg = null){
        return $this->mproxy->updateFaq($request->input(), $arg);
    }

    public function addFaq(){
        $faqCatList = $this->mproxy->getFaqCatList(true);
        return view('faq_add', ['faqCatList' => $faqCatList]);
    }

    public function saveFaq(Request $request){
        return $this->mproxy->saveFaq($request->input());
    }

    public function appSettings(){
        $settings = $this->mproxy->getAppSettings();
        return view('app_setting', ['settings' => $settings]);
    }

    public function updateAppSettings(Request $request){
        if($request->hasFile('imageUpload')){
            $file = $request->file('imageUpload');
            $this->mproxy->updateAppSplashScreen($file);
        }
        else{
            $inputs = $request->input();
            $this->mproxy->updateAppSettings($inputs);
        }

        return back()->with('msg', $this->prepareMessage(true, 'Updated Successfully!'));
    }

    public function searchTerm(Request $request){
        $term = $request->input('term');
        $transactions = $this->mproxy->filterTransactionByTransId($term);
        if($transactions != null){
            return view('transaction_list', ['data' => $transactions]);
        }

        $users = $this->mproxy->filterUsers($term);
        if($users != null){
            return view('user_list', ['data' => $users]);
        }

        return view("error", ['code' => '404', "msg" => "Not found", "reason" => "Sorry! no record found for your search"]);
    }
}
