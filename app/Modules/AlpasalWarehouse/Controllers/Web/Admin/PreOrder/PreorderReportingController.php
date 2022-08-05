<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Admin\PreOrder;

use App\Modules\AlpasalWarehouse\Services\PreOrder\WarehousePreOrderService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Services\PreOrder\StorePreOrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreorderReportingController extends BaseController
{
    public $title = 'Alpasal PreOrder Reporting';
    public $base_route = 'admin.pre-orders-reporting.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view = 'admin.pre-order-reportings.';

    private $warehousePreOrderService,$storePreOrderService;

    public function __construct(WarehousePreOrderService $warehousePreOrderService,
    StorePreOrderService $storePreOrderService
    )
    {
        $this->middleware('permission:View Preorder Reporting',
            ['only' => ['getPreordersReportingForm','getPreordersReporting']]);

        $this->warehousePreOrderService = $warehousePreOrderService;
        $this->storePreOrderService = $storePreOrderService;
    }

    public function getPreordersReportingForm()
    {
        try{
            return view($this->loadViewData($this->module . $this->view . 'index'));
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
    public function getPreordersReporting($storeCode,$preorderCode,Request $request)
    {
        try {
            $filterParameters = [
                'store_code' => $storeCode,
                'preorder_code' => $preorderCode,
            ];
            $store = $this->warehousePreOrderService->findStoreByCode($filterParameters);
            $preOrder = $this->warehousePreOrderService->findPreorderByCode($filterParameters);
//            $preOrderProducts = $this->storePreOrderService->getStorePreOrderDetails($filterParameters['preorder_code'],$filterParameters,['warehousePreOrderListing']);
            $preOrderProducts = $this->warehousePreOrderService->getPreOrderProducts($filterParameters);
            $amount = $this->warehousePreOrderService->getPreOrderAmount($filterParameters);
            $deletedProducts = $this->warehousePreOrderService->deletedProducts($filterParameters);
            $deactiveProducts = $this->warehousePreOrderService->deactiveProducts($filterParameters);
            $activeProducts = $this->warehousePreOrderService->activeProducts($filterParameters);

            if ($request->ajax()) {
                return view($this->loadViewData($this->module . $this->view . 'preorder-info'),
                    compact('store','preOrder','preOrderProducts',
                        'amount','deletedProducts','deactiveProducts','activeProducts'))
                    ->render();
            }
           return $store;
        } catch (Exception $exception) {
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }

}
