<?php


namespace App\Modules\InventoryManagement\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;

use App\Modules\InventoryManagement\Exports\InventorySales\StoreInventoryCurrentStockRecordExport;
use App\Modules\InventoryManagement\Helpers\StoreCurrentStockRecordHelper;
use App\Modules\Product\Helpers\ProductPackagingContainsHelper;
use App\Modules\Product\Services\ProductService;
use App\Modules\Store\Services\StoreService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StoreInventoryCurrentStockController extends BaseController
{
    public $title = 'Store Current Stock Record ';
    public $base_route = 'admin.inventory.current-stock';
    public $sub_icon = 'file';
    public $module = 'InventoryManagement::';
    public $view = 'inventory-current-stock.';

    public $storeService;
    public $productService;

    public function __construct( StoreService $storeService,
                                 ProductService $productService)
    {
        $this->storeService = $storeService;
        $this->productService = $productService;
    }
    public function index(Request $request)
    {
        try {
            $filterParameters = [
                'store_code' => $request->get('store_code')? $request->get('store_code'):'S1003',
                'product_code' => $request->get('product_code'),
                'expiry_date_from' => $request->get('expiry_date_from'),
                'expiry_date_to' => $request->get('expiry_date_to'),
                'perPage' => $request->get('per_page')?  $request->get('per_page') : 25,
                'download_excel' => $request->get('download_excel')? true : false
            ];
            $stores = $this->storeService->getAllActiveStores($with=[],$select=['store_code','store_name']);
            $products = $this->productService->getAllVerifiedProducts($select=['product_code','product_name']);
            if($request->ajax() || $filterParameters['download_excel']) {
                $storeCurrentStockDetail = StoreCurrentStockRecordHelper::getStoreInventoryProductCurrentStockDetail($filterParameters);
                $storeCurrentStockDetail->getCollection()->transform(function ($storeCurrentStockPackageContains, $key) {
                    $storeCurrentStockPackageContains->package_contains = implode(' ', ProductPackagingContainsHelper::getProductPackagingContainsByPPHCode($storeCurrentStockPackageContains->pph_code));
                    return $storeCurrentStockPackageContains;
                });
                if($filterParameters['download_excel']){
                    return Excel::download( new StoreInventoryCurrentStockRecordExport($storeCurrentStockDetail),'stock-statement.xlsx');
                }
                return view(Parent::loadViewData($this->module . $this->view . 'current-stock-detail-table'),
                    compact('storeCurrentStockDetail','filterParameters'))->render();
            }
            return view(Parent::loadViewData($this->module . $this->view . 'index'),
                compact('stores','products','filterParameters'));
        } catch (\Exception $exception) {
            if($request->ajax()){
                return sendErrorResponse($exception->getMessage(),$exception->getCode());
            }
            return redirect()->back()->with('danger',$exception->getMessage());

        }
    }


}

