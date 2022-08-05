<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 12/11/2020
 * Time: 1:12 PM
 */

namespace App\Modules\Dashboard\Controllers\Warehouse;


use App\Modules\AlpasalWarehouse\Helpers\WarehouseDispatch\WarehouseDispatchRouteHelper;
use App\Modules\AlpasalWarehouse\Helpers\WarehouseDispatch\WarehouseDispatchRouteStoreOrderHelper;
use App\Modules\Application\Controllers\BaseController;
use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Brand\Models\Brand;
use App\Modules\Cart\Helpers\CartHelper;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Utilities\IProductUnitPackagePriceCalculator;
use App\Modules\Store\Helpers\StoreOrderHelper;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\StoreOrderDetails;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseDashboardController extends BaseController
{

    protected $module='Dashboard::';
    protected $view = 'warehouse.';

    public function __construct()
    {
        $this->middleware('permission:View Warehouse Dashboard', ['only' => 'index']);
    }

    public function index(Request $request)
    {
        $authWarehouse = getAuthWarehouse();

        return view($this->module.$this->view.'index',compact('authWarehouse'));
    }
}
