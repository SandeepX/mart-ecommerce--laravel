<?php


namespace App\Modules\Reporting\Controllers\Web\Admin;

use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\Product\Utilities\ProductPackagingFormatter;
use App\Modules\Reporting\Exports\DemandProjection\DemandProjectionExport;
use App\Modules\Reporting\Helpers\DemandProjectionHelper;
use App\Modules\Vendor\Repositories\VendorProductPackagingHistoryRepository;
use App\Modules\Vendor\Services\VendorService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DemandProjectionController Extends BaseController
{
    public $title = 'Warehouse Demand Projection';
    public $base_route = 'admin.demand-projection.';
    public $sub_icon = 'file';
    public $module = 'Reporting::';

    private $view = 'admin.wh-demand-projection.';

    public $warehouseService;
    public $vendorService;
    public $vendorProductPackagingHistoryRepository;

    public function __construct(
        WarehouseService $warehouseService,
        VendorService $vendorService,
        VendorProductPackagingHistoryRepository  $vendorProductPackagingHistoryRepository
    ){
        $this->warehouseService = $warehouseService;
        $this->vendorService = $vendorService;
        $this->vendorProductPackagingHistoryRepository = $vendorProductPackagingHistoryRepository;
    }

    public function warehouseDemandProjectionReport(Request $request)
    {
        try{
            $page = $request->get('page') ? $request->get('page') : 1;
            $limit = $request->get('limit') ? $request->get('limit') : 100;
            $offset = (($page - 1) * $limit);
            $filterParameters = [
               'warehouse_code' => ($request->get('warehouse_code')) ? $request->get('warehouse_code'):'AW1000',
               'vendor_code' => $request->get('vendor_code'),
               'product_name' => $request->get('product_name'),
               'product_variant_name' => $request->get('product_variant_name'),
               'limit' => $limit,
               'paginate'=> ' LIMIT '.$limit. ' OFFSET '.$offset,
               'download_excel' => $request->get('download_excel') ?? false
            ];

            //dd($filterParameters['vendor_code']);

            $warehouse = $this->warehouseService->getAllWarehouses();
            $vendors = $this->vendorService->getAllVendors();
            $warehouseName = $this->warehouseService->findOrFailWarehouseByCode($filterParameters['warehouse_code'])->warehouse_name;
            $demandProjection = DemandProjectionHelper::getWarehouseDemandProjectionReport($filterParameters);
            $filterParameters['total_no_of_pages'] = ceil((array_pop($demandProjection)/$limit));
            array_walk($demandProjection,function(&$value,$k) {
                $productPackagingFormatter = new ProductPackagingFormatter();
                $latestPackagingDetails = $this->vendorProductPackagingHistoryRepository->getProductPackagingHistoryByProductCodeAndVariantCode(
                    $value->product_code,
                    $value->product_variant_code
                );
                if ($latestPackagingDetails){
                    $arr =[];
                    if ($latestPackagingDetails->micro_unit_code){
                        $arr[1] =$latestPackagingDetails->microPackageType->package_name;
                    }
                    if ($latestPackagingDetails->unit_code){
                        $arrKey = intval($latestPackagingDetails->micro_to_unit_value);
                        $arr[$arrKey] =$latestPackagingDetails->unitPackageType->package_name;
                    }
                    if ($latestPackagingDetails->macro_unit_code){
                        $arrKey = intval($latestPackagingDetails->micro_to_unit_value *
                            $latestPackagingDetails->unit_to_macro_value);
                        $arr[$arrKey] =$latestPackagingDetails->macroPackageType->package_name;
                    }
                    if ($latestPackagingDetails->super_unit_code){
                        $arrKey = intval($latestPackagingDetails->micro_to_unit_value *
                            $latestPackagingDetails->unit_to_macro_value *
                            $latestPackagingDetails->macro_to_super_value);
                        $arr[$arrKey] =$latestPackagingDetails->superPackageType->package_name;
                    }
                    $arr=array_reverse($arr,true);
                    $value->total_purchase_qty =  $productPackagingFormatter->formatPackagingCombination($value->total_purchase_qty,$arr);
                    $value->total_received_qty =  $productPackagingFormatter->formatPackagingCombination($value->total_received_qty,$arr);
                    $value->normal_order_dispacthed_qty =  $productPackagingFormatter->formatPackagingCombination($value->normal_order_dispacthed_qty,$arr);
                    $value->pre_order_dispatched_qty =  $productPackagingFormatter->formatPackagingCombination($value->pre_order_dispatched_qty,$arr);
                    $value->total_stock_transfer_qty =  $productPackagingFormatter->formatPackagingCombination($value->total_stock_transfer_qty,$arr);
                    $value->normal_order_demand_qty =  $productPackagingFormatter->formatPackagingCombination($value->normal_order_demand_qty,$arr);
                    $value->demand_preorder_qty =  $productPackagingFormatter->formatPackagingCombination($value->demand_preorder_qty,$arr);
                    $value->actual_stock =  $productPackagingFormatter->formatPackagingCombination($value->actual_stock,$arr);
                    $value->demand_stock =  $productPackagingFormatter->formatPackagingCombination($value->demand_stock,$arr);
                    if($value->demand_projection >= 0){
                        $value->demand_projection =$productPackagingFormatter->formatPackagingCombination($value->demand_projection,$arr);
                    }else{
                        $value->demand_projection = '-'.$productPackagingFormatter->formatPackagingCombination(-1 * $value->demand_projection,$arr);
                    }
                }
            });

            if($filterParameters['download_excel']){
                return Excel::download(new DemandProjectionExport($demandProjection,$warehouseName), 'warehouse-demand-projection.xlsx');
            }

            return view( Parent::loadViewData($this->module.$this->view.'index'),
                compact('demandProjection',
                    'filterParameters',
                    'page',
                    'warehouse',
                    'vendors'
                )
            );
        }catch(\Exception $exception){
            return redirect()->route('admin.dashboard')->with('danger', $exception->getMessage());
        }

    }

}
