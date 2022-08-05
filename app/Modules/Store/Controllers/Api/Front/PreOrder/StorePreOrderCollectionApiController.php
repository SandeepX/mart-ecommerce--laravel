<?php


namespace App\Modules\Store\Controllers\Api\Front\PreOrder;


use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Resources\StorePreOrderCollection;
use App\Modules\AlpasalWarehouse\Resources\WarehousePreOrderResource;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderService;
use App\Modules\Category\Services\CategoryService;
use App\Modules\Store\Helpers\StoreWarehouseHelper;
use App\Modules\Store\Services\PreOrder\StorePreOrderService;
use Illuminate\Http\Request;

class StorePreOrderCollectionApiController extends Controller
{
    private $warehousePreOrderService,$storePreOrderService,$categoryService;

    public function __construct(
        WarehousePreOrderService $warehousePreOrderService,
        StorePreOrderService $storePreOrderService,
        CategoryService $categoryService
    ){
        $this->warehousePreOrderService = $warehousePreOrderService;
        $this->storePreOrderService = $storePreOrderService;
        $this->categoryService = $categoryService;
    }
  public function getStorePreOrderProductCollections(Request $request){
      $paginateBy = $request->filled('records_per_page')
                             ? $request->records_per_page : null;


      $warehouseCode= StoreWarehouseHelper::getFirstActiveWarehouseCodeAssociatedWithStore(getAuthStoreCode());
      $preOrders =$this->warehousePreOrderService
                       ->getDisplayableLimitedWarehousePreOrdersByWarehouseCode(
                              $warehouseCode,
                              $paginateBy,
                              []
                   );

//      $preOrders = $preOrders->map(function ($preOrder){
//          $preOrder->can_pre_order = false;
//          if($preOrder->isPreOrderable()){
//              $preOrder->can_pre_order = true;
//         }
//          return $preOrder;
//      });
//
//      //dd($preOrders);
      $preOrders = new StorePreOrderCollection($preOrders);
      return sendSuccessResponse('Data Found', $preOrders);
  }
}
