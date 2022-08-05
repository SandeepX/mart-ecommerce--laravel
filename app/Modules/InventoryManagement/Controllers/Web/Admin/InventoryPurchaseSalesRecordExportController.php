<?php


namespace App\Modules\InventoryManagement\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\InventoryManagement\Exports\InventorySales\StoreInventorySalesRecordExport;
use App\Modules\InventoryManagement\Helpers\StoreInventoryStockSalesHelper;
use App\Modules\Product\Helpers\ProductPackagingContainsHelper;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Exception;

class InventoryPurchaseSalesRecordExportController extends BaseController
{

    public function salesRecordExcelExport(Request $request)
    {
        try {
            $filterParameters = [
                'store_code' => $request->get('store_code'),
                'product_code' => $request->get('product_code'),
                'sales_from' => $request->get('sales_from'),
                'sales_to' => $request->get('sales_to'),
                'perPage' => $request->get('per_page')?  $request->get('per_page') : 25,
                'page' => $request->get('page') ? (int)$request->get('page') : 1
            ];
            $storeInventoryStockDispatchedDetail = StoreInventoryStockSalesHelper::getStoreInventoryProductSalesRecordDetail($filterParameters);
            $storeInventoryStockDispatchedDetail->getCollection()->transform(function ($storeDispatchedStockPackageContains,$key){
                $storeDispatchedStockPackageContains->package_contains = implode(' ',ProductPackagingContainsHelper::getProductPackagingContainsByPPHCode($storeDispatchedStockPackageContains->pph_code));
                return $storeDispatchedStockPackageContains;
            });
            return Excel::download(new StoreInventorySalesRecordExport(
                $storeInventoryStockDispatchedDetail ), 'store-inventory-sales-record.xlsx');

        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }


}

