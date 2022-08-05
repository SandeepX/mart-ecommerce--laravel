<?php


namespace App\Modules\InventoryManagement\Controllers\Web\Admin;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\InventoryManagement\Helpers\StoreInventoryStockSalesHelper;
use App\Modules\InventoryManagement\Models\StoreInventoryItemDispatched;
use App\Modules\InventoryManagement\Services\StoreInventorySalesService;
use App\Modules\Product\Helpers\ProductPackagingContainsHelper;
use App\Modules\Product\Services\ProductService;
use App\Modules\Store\Services\StoreService;
use Illuminate\Http\Request;

class StoreInventoryStockSalesRecordController extends BaseController
{
    public $title = 'Inventory Sales';
    public $base_route = 'admin.inventory.sales';
    public $sub_icon = 'file';
    public $module = 'InventoryManagement::';
    public $view = 'inventory-sales.';

    public $inventoryStockSalesService;
    public $storeService;
    public $productService;

    public function __construct(StoreInventorySalesService $inventoryStockSalesService,
                                StoreService $storeService,
                                ProductService $productService)
    {
        $this->inventoryStockSalesService = $inventoryStockSalesService;
        $this->storeService = $storeService;
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        try{
            $filterParameters = [
                'store_code' => $request->get('store_code'),
                'product_code' => $request->get('product_code'),
                'sales_from' => $request->get('sales_from'),
                'sales_to' => $request->get('sales_to'),
                'perPage' => $request->get('per_page')?  $request->get('per_page') : 25,
                'page' => $request->get('page') ? (int)$request->get('page') : 1
            ];
            $stores = $this->storeService->getAllActiveStores($with=[],$select=['store_code','store_name']);
            $products = $this->productService->getAllVerifiedProducts($select=['product_code','product_name']);
            $paymentType = StoreInventoryItemDispatched::PAYMENT_TYPE;

            if($request->ajax()) {

                $storeInventoryStockDispatchedDetail = StoreInventoryStockSalesHelper::getStoreInventoryProductSalesRecordDetail($filterParameters);
                $storeInventoryStockDispatchedDetail->getCollection()->transform(function ($storeDispatchedStockPackageContains,$key){
                    $storeDispatchedStockPackageContains->package_contains = implode(' ',ProductPackagingContainsHelper::getProductPackagingContainsByPPHCode($storeDispatchedStockPackageContains->pph_code));
                    return $storeDispatchedStockPackageContains;
                });

                return view( Parent::loadViewData($this->module.$this->view.'inventory-sales-partial-table'),
                    compact('storeInventoryStockDispatchedDetail','filterParameters'))->render();
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

    public function showStoreInventorySalesRecord($SIIDCode,$PPHCode)
    {
        try{
            $with= ['storeInventoryItemDetail:siid_code,store_inventory_code,cost_price,mrp,manufacture_date,expiry_date'];
            $select = ['siid_code','package_code','quantity','selling_price','payment_type','created_at','updated_at'];
            $inventoryStockSalesRecordDetail = $this->inventoryStockSalesService
                ->getStoreSalesRecordBySIIDAndPPHCode($SIIDCode,$PPHCode,$with,$select);
            $packageDetail = implode(' ', ProductPackagingContainsHelper::getProductPackagingContainsByPPHCode($PPHCode));
            return view(Parent::loadViewData($this->module . $this->view . 'show-sales-record-detail'),
                compact('inventoryStockSalesRecordDetail','packageDetail'));
        }catch(\Exception $exception){
            return redirect()->back()->with('danger',$exception->getMessage());
        }
    }

}
