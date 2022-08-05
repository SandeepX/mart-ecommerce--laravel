<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\PreOrder;

use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderService;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehouseStorePreOrderService;
use App\Modules\Application\Controllers\BaseController;

use App\Modules\Store\Helpers\StoreTransactionHelper;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Services\PreOrder\StorePreOrderBillService;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class WarehousePreOrderRollbackController extends BaseController
{

    public $title = 'Store PreOrder';
    public $base_route = 'warehouse.warehouse-pre-orders.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.warehouse-pre-orders.store-pre-orders.';

    private $warehouseStorePreOrderService,$storePreOrderBillService;
    private $warehousePreOrderService;

    public function __construct(
        WarehouseStorePreOrderService $warehouseStorePreOrderService,
        StorePreOrderBillService $storePreOrderBillService,
        WarehousePreOrderService  $warehousePreOrderService
    ){

        $this->warehouseStorePreOrderService = $warehouseStorePreOrderService;
        $this->storePreOrderBillService = $storePreOrderBillService;
        $this->warehousePreOrderService = $warehousePreOrderService;
    }

    public function rollback(Request $request,$warehousePreOrderListingCode){
//        dd($warehousePreOrderListingCode);

        try{

            $preOrder = WarehousePreOrderListing::where('warehouse_preorder_listing_code',$warehousePreOrderListingCode)
                      //->where('is_finalized',1)
//                      ->whereDate('updated_at','2021-04-27')
                      ->firstOrFail();

           // dd($preOrder);

            if($preOrder){
                $storePreOrders = StorePreOrder::where('warehouse_preorder_listing_code',
                    $preOrder->warehouse_preorder_listing_code)
                    ->where('status','cancelled')
                    ->get();

                $data = [];
                $change = [];
                DB::beginTransaction();
                foreach ($storePreOrders as $storePreOrder){

                    $totalAmount = $storePreOrder->storePreOrderView->total_price;
                    $currentBalance = StoreTransactionHelper::getLatestStoreCumulativeBalance($storePreOrder->store_code);

                    if($currentBalance >= $totalAmount){
                        if($request->get('commit') == 'true'){

                            $storePreOrder->update(['status'=>'pending','payment_status'=>0]);
                            array_push($change,$storePreOrder);
                        }else{
                          array_push($data,$storePreOrder);
                        }
                    }
                }
                 if($request->get('commit') == true){
                     $preOrder->update(['is_finalized'=>0]);
                 }else{
                     dd($data,$change);
                 }
            }
           DB::commit();

          dd('Sucecss',$data,$change);

        }catch (Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }

    }



}
