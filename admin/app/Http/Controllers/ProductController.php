<?php

namespace App\Http\Controllers;

use App\Model\ActiveStatus;
use App\Model\TableEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    private $DATA_SERVICE = 'DATA_PUR_01';
    private $RECHARGE_SERVICE = 'RE_SA_01';
    private $PAY_BILLS = "BILL_PAYMENT_001";
    private $BUY_AIRTIME = "BUY_AIRTIME_001";

    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'authorization']);
        parent::__construct();
    }

    private function productList($service_id = null, $product_id = null, $sub_prod_id = null ){
        $productList = $this->mproxy->getProductsByServiceId($service_id);
        if($product_id != null)
            $subProdList = $this->mproxy->getSubProductsByProdId($product_id);
        if($sub_prod_id != null)
            $subProdDetail = $this->mproxy->getSubProductDetail($sub_prod_id);

        return view('product_list', ['productList' => $productList, 'subProdList' => $subProdList ?? null, 'subProdDetail' => $subProdDetail ?? null]);
    }
    public function dataList(Request $request, $arg = null, $arg2 = null){
        return $this->productList($this->DATA_SERVICE, $arg, $arg2);
    }
    public function rechargeList(Request $request, $arg = null, $arg2 = null){
        return $this->productList($this->RECHARGE_SERVICE, $arg, $arg2);
    }
    public function payBillsList(Request $request, $arg= null, $arg2 = null){
        return $this->productList($this->PAY_BILLS, $arg, $arg2 );
    }
    public function buyAirtimeList(Request $request, $arg= null, $arg2 = null){
        return $this->productList($this->BUY_AIRTIME, $arg, $arg2 );
    }

    public function allSubProducts(){
        $subProdList = $this->mproxy->getAllSubProducts($this->user->email);
        return view('sub_product_list', ['subProdList' => $subProdList ]);
    }

    public function deleteSubProduct(Request $request, $arg){
        return $this->mproxy->updateSubProductStatus($arg, ActiveStatus::InActive);
    }
    public function activateSubProduct(Request $request, $arg){
        return $this->mproxy->updateSubProductStatus($arg, ActiveStatus::Active);
    }
    public function deleteProduct(Request $request, $arg){
        return $this->mproxy->updateProductStatus($arg, ActiveStatus::InActive);
    }
    public function activateProduct(Request $request, $arg){
        return $this->mproxy->updateProductStatus($arg, ActiveStatus::Active);
    }

    public function appProduct($service_id){
        return view('product_add', ['service_id' => $service_id]);
    }
    public function addData(Request $request){
        return $this->appProduct($this->DATA_SERVICE);
    }
    public function addRecharge(Request $request){
        return $this->appProduct($this->RECHARGE_SERVICE);
    }
    public function addPayBills(Request $request){
        return $this->appProduct($this->PAY_BILLS);
    }
    public function addBuyAirtime(Request $request){
        return $this->appProduct($this->BUY_AIRTIME);
    }

    public function addSubProd(Request $request, $arg = null){
        $product = $this->mproxy->getProductById($arg);
        if($product == null) return redirect()->intended('dashboard');
        return view('product_sub_add', ['product' => $product]);
    }
    public function saveProduct(Request $request){
        return $this->mproxy->saveNewProduct($request->input(), $request->file('imageUpload'));
    }
    public function saveSubProd (Request $request){
        return $this->mproxy->saveNewSubProduct($request->input());
    }
    public function updateSubProduct(Request $request){
        $inputs = $request->input();
        return $this->mproxy->updateSubProduct($inputs);

    }
}
