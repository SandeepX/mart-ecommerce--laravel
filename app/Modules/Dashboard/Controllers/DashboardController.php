<?php

namespace App\Modules\Dashboard\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Brand\Models\Brand;
use App\Modules\Category\Models\CategoryMaster;
use App\Modules\Dashboard\Helpers\DashboardHelper;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Store\Models\Store;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Http\Request;


/**
 * Class DashboardController
 * @package App\Modules\Dashboard\Controllers
 */
class DashboardController extends Controller
{

    protected $module;
    protected $view;

    public function __construct()
    {
        $this->module = 'Dashboard';
    }

    public function index(Request $request)
    {
        $totalProducts =number_format(ProductMaster::count());
        $totalVendors = number_format(Vendor::count());
        $totalStores = number_format(Store::count());
        $totalBrands = number_format(Brand::count());
        $totalWarehouses = number_format(Warehouse::count());

        $totalSalesAmount =(DashboardHelper::getTotalSalesAmount());
        $totalPurchaseAmount = (DashboardHelper::getTotalPurchaseAmount());
        $totalStoresBalance =(DashboardHelper::getTotalStoresBalance());
        $warehousesTotalProductStock = number_format(DashboardHelper::getWarehousesTotalProductStock());


        $totalProductCategories = number_format(CategoryMaster::count());
        $totalNumberOfSalesQuantity = number_format(DashboardHelper::getTotalNumberOfSalesQuantity());
        $totalNumberOfPurchaseQuantity = number_format(DashboardHelper::getTotalNumberOfPurchaseQuantity());
        $totalPendingSalesOrders = number_format(DashboardHelper::getTotalPendingSalesOrders());
        $totalPendingPurchaseOrders = number_format(DashboardHelper::getTotalPendingPurchaseOrders());

      return view($this->module.'::index',compact('totalProducts','totalVendors',
          'totalStores','totalBrands','totalWarehouses','totalSalesAmount',
          'totalPurchaseAmount','totalStoresBalance','warehousesTotalProductStock',
          'totalProductCategories','totalNumberOfSalesQuantity','totalNumberOfPurchaseQuantity',
          'totalPendingSalesOrders','totalPendingPurchaseOrders'));
    }

}
