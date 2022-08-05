<?php


namespace App\Modules\InventoryManagement\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\InventoryManagement\Helpers\InventoryPurchaseStockHelper;
use App\Modules\InventoryManagement\Services\InventoryCurrentStockQtyDetailService;
use App\Modules\InventoryManagement\Services\InventoryCurrentStockService;
use App\Modules\Product\Helpers\ProductPackagingContainsHelper;
use App\Modules\Product\Services\ProductService;
use App\Modules\Store\Services\StoreService;
use Illuminate\Http\Request;

class InventoryPurchaseStockController  extends BaseController
{
    public $title = 'Store Purchase Record ';
    public $base_route = 'admin.inventory.purchased-stock';
    public $sub_icon = 'file';
    public $module = 'InventoryManagement::';
    public $view = 'inventory-purchased-stock.';

    public $inventoryCurrentStockService;
    public $inventoryCurrentStockQtyDetailService;
    public $storeService;
    public $productService;


    public function __construct(
        InventoryCurrentStockService $inventoryCurrentStockService,
        InventoryCurrentStockQtyDetailService $inventoryCurrentStockQtyDetailService,
        StoreService $storeService,
        ProductService $productService
    )
    {
        $this->inventoryCurrentStockService = $inventoryCurrentStockService;
        $this->inventoryCurrentStockQtyDetailService = $inventoryCurrentStockQtyDetailService;
        $this->storeService = $storeService;
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        try{
            $filterParameters = [
                'store_code' => $request->get('store_code'),
                'product_code' => $request->get('product_code'),
                'expiry_date_from' => $request->get('expiry_date_from'),
                'expiry_date_to' => $request->get('expiry_date_to'),
                'perPage' => $request->get('per_page')?  $request->get('per_page') : 25,
                'page' => $request->get('page') ? (int)$request->get('page') : 1
            ];
            $stores = $this->storeService->getAllActiveStores($with=[],$select=['store_code','store_name']);
            $products = $this->productService->getAllVerifiedProducts($select=['product_code','product_name']);
            if($request->ajax()) {
                $storeCurrentStockDetail = InventoryPurchaseStockHelper::getStoreInventoryCurrentProductStockDetail($filterParameters);
                $storeCurrentStockDetail->getCollection()->transform(function ($storeCurrentStockPackageContains,$key){
                    $storeCurrentStockPackageContains->package_contains = implode(' ',ProductPackagingContainsHelper::getProductPackagingContainsByPPHCode($storeCurrentStockPackageContains->pph_code));
                    return $storeCurrentStockPackageContains;
                });
                return view( Parent::loadViewData($this->module.$this->view.'inventory-purchased-stock-partial-table'),
                    compact('storeCurrentStockDetail','filterParameters'))->render();
            }
            return view(Parent::loadViewData($this->module . $this->view . 'index'),
                compact('stores','products','filterParameters'));
        }catch(\Exception $exception){
            if($request->ajax()){
                return sendErrorResponse($exception->getMessage(),$exception->getCode());
            }
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

    public function showInventoryStockRecievedQtyDetail($siid_code,$pph_code)
    {
        try{
            $with= ['storeInventoryItemDetail'];
            $purchasedStockQtyRecievedDetail = $this->inventoryCurrentStockQtyDetailService
                ->getCurrentStockQtyRecievedDetailBySIIDAndPPHCode($siid_code, $pph_code,$with);
            $packageDetail = implode(' ', ProductPackagingContainsHelper::getProductPackagingContainsByPPHCode($pph_code));
            return view(Parent::loadViewData($this->module . $this->view . 'show-recieved-qty-detail'),
                compact('purchasedStockQtyRecievedDetail','packageDetail'));
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

}
