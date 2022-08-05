<?php

namespace App\Modules\AlpasalWarehouse\Controllers\Web\Admin\PurchaseOrder;
use App\Modules\AlpasalWarehouse\Helpers\WarehousePurchaseOrderFilter;
use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrder;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;

use App\Modules\Vendor\Services\VendorService;
use Exception;
use Illuminate\Http\Request;

class WarehousePurchaseOrderController extends BaseController
{
    public $title = 'Alpasal Warehouse Purchase Order';
    public $base_route = 'admin.warehouse-purchase-orders.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='admin.new-warehouse-purchase-orders.';

    private $vendorService,$warehouseService;

    public function __construct(VendorService $vendorService,WarehouseService $warehouseService){

//        $this->middleware('permission:View List Of WH Purchase Orders',
//            ['only' => ['index']]);

        $this->vendorService = $vendorService;
        $this->warehouseService = $warehouseService;
    }


    public function index(Request $request)
    {
        try{

            $filterParameters = [
                'vendor_code' => $request->get('vendor'),
                'warehouse_code' =>$request->get('warehouse'),
                'status' => $request->get('status'),
                'order_date_from' => $request->get('order_date_from'),
                'order_date_to' => $request->get('order_date_to'),
            ];

            $with=[
                'vendor','warehouse'
            ];
            $statuses=WarehousePurchaseOrder::STATUSES;
            $vendors = $this->vendorService->getAllActiveVendors();
            $warehouses= $this->warehouseService->getAllWarehouses();
            $purchaseOrders =WarehousePurchaseOrderFilter::filterPaginatedWarehousePurchaseOrders($filterParameters,10,$with);
            return view($this->loadViewData($this->module.$this->view.'index'),compact('purchaseOrders',
                'statuses','vendors','warehouses','filterParameters'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());

        }

    }



}
