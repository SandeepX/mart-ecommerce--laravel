<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\PreOrder;

use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderTargetHelper;
use App\Modules\AlpasalWarehouse\Requests\PreOrder\WarehousePreOrderTargetRequest;
use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderTargetService;
use App\Modules\Application\Controllers\BaseController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehousePreOrderTargetController extends BaseController
{
    public $title = 'Alpasal Warehouse PreOrder';
    public $base_route = 'warehouse.warehouse-pre-orders.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.warehouse-pre-orders.';

    private $warehousePreOrderTargetService;

    public function __construct(WarehousePreOrderTargetService $warehousePreOrderTargetService){
       // $this->middleware('permission:View Target List', ['only' => ['index']]);
        $this->middleware('permission:Create PreOrder Target', ['only' => ['create','store']]);
        $this->middleware('permission:Show PreOrder Target', ['only' => ['show']]);
        $this->warehousePreOrderTargetService=$warehousePreOrderTargetService;
    }

    public function create($preOrderListingCode,Request $request)
    {
        try{
            $preOrderListing=WarehousePreOrderTargetHelper::preOrderTargetable($preOrderListingCode);
            $storeTypes=$this->warehousePreOrderTargetService->getStoreTypes();
            if ($request->ajax()) {
                return view('AlpasalWarehouse::warehouse.warehouse-pre-orders.common.pre-order-target-table',
                    compact('storeTypes','preOrderListing','preOrderListingCode'))->render();
            }
            return $storeTypes;
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function store($preOrderListingCode,WarehousePreOrderTargetRequest $request)
    {
        DB::beginTransaction();
        try{
            $validated = $request->validated();

            $this->warehousePreOrderTargetService->storeWarehousePreOrderTarget($preOrderListingCode,$validated);
            DB::commit();
         return  session()->flash('success', 'PreOrder set successfully');
        }catch (Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function show($preOrderListingCode,Request $request)
    {
        try{
            $storeTypeTargets=$this->warehousePreOrderTargetService->getStoreTypeTargets($preOrderListingCode);
          $preOrderTargets=  $this->warehousePreOrderTargetService->getPreOrderTargetsOfPreOrderListing($preOrderListingCode);
//           dd($preOrderTargets);
            if ($request->ajax()) {
                return view('AlpasalWarehouse::warehouse.warehouse-pre-orders.common.pre-order-target-show-table',
                    compact('preOrderTargets','storeTypeTargets','preOrderListingCode'))->render();
            }
            return  $preOrderTargets;
        }catch (Exception $exception){
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
