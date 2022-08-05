<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Admin\PreOrder;

use App\Modules\AlpasalWarehouse\Exports\Admin\PreOrder\PreorderReportingExport;
use App\Modules\AlpasalWarehouse\Helpers\PreOrder\WarehousePreOrderHelper;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Location\Services\LocationHierarchyService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportingController extends BaseController
{
    public $title = 'Alpasal PreOrder Reporting';
    public $base_route = 'admin.reporting.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view = 'admin.reporting.';

    private $locationHierarchyService,$warehouseService;

    public function __construct(LocationHierarchyService $locationHierarchyService,
                                WarehouseService $warehouseService)
    {
        $this->locationHierarchyService = $locationHierarchyService;
        $this->warehouseService = $warehouseService;
    }

    public function getReportingData(Request $request)
    {
        try {
            $filterParameters = [
                'store_name' => $request->get('store_name'),
                'store_owner' => $request->get('store_owner'),
                'warehouse_code' => $request->get('warehouse_code'),
                'province' => $request->get('province'),
                'district' => $request->get('district'),
                'municipality' => $request->get('municipality'),
                'ward' => $request->get('ward'),
                'pre_order_name' => $request->get('pre_order_name'),
            ];

            $provinces = $this->locationHierarchyService->getAllLocationsByType('province');
            $warehousePreOrders = WarehousePreOrderHelper::getReportingData($filterParameters);
            $warehouses = $this->warehouseService->getAllWarehouses();
            //dd($warehousePreOrders);
            return view($this->loadViewData($this->module . $this->view . 'index'),
                compact('warehousePreOrders','filterParameters','provinces','warehouses'));

        } catch (Exception $exception) {
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }

    public function excelExportReport()
    {
        try {

            $warehousePreOrders = WarehousePreOrderHelper::getReportingDataForExcel();
            return Excel::download(new PreorderReportingExport($warehousePreOrders), 'preorder_reporting.xlsx');

        } catch (Exception $exception) {
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }
    }
}
