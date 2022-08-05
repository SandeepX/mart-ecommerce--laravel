<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse;


use App\Modules\AlpasalWarehouse\Helpers\Store\StoreWarehouseHelper;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\AlpasalWarehouse\Services\WarehouseService;
use App\Modules\AlpasalWarehouse\Transformers\StoreDetailTransformer;
use App\Modules\Application\Controllers\BaseController;

use App\Modules\Product\Models\ProductMaster;
use App\Modules\Store\Classes\StoreBalance;
use App\Modules\Store\Services\StoreService;
use Exception;
use Illuminate\Http\Request;

class WHStoreConnectionController extends  BaseController
{

    public $title = 'WH Store Connections';
    public $base_route = 'warehouse.store.connections';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.store-connections.';

    private $warehouseService;
    private $storeService;
    private $storeBalance;

    public function __construct(
        WarehouseService $warehouseService,
        StoreService $storeService,
        StoreBalance $storeBalance
    )
    {
        $this->middleware('permission:View WH Store Connection', ['only' => ['getConnectedStores', 'getStoreDetail']]);
        $this->storeService = $storeService;
        $this->warehouseService=$warehouseService;
        $this->storeBalance=$storeBalance;
    }

    public function getConnectedStores(Request $request){
        try{

            $filterParameters = [
                'store_name' => $request->get('store_name'),
                'store_owner_name' => $request->get('store_owner_name')
            ];
            $warehouseCode = getAuthWarehouseCode();
            $connectedStores = StoreWarehouseHelper::getConnectedWHStores($warehouseCode,$filterParameters);
            $connectedStores->getCollection()->transform(function ($connectedStore,$key){
                $store =  $this->storeService->findOrFailStoreByCode($connectedStore->store_code);
                $connectedStore->current_balance = $this->storeBalance->getStoreWalletCurrentBalance($store);
                return $connectedStore;
            });
        //    dd($connectedStores);
            return view($this->loadViewData($this->module.$this->view.'index'),compact('connectedStores','filterParameters'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
    public function getStoreDetail($storeCode)
    {
        try {
            $warehouseCode = getAuthWarehouseCode();
            $store = $this->warehouseService->findOrFailStoreByCode($storeCode,$warehouseCode);
            if(isset($store) && $store->count())
            {
                $store = (new StoreDetailTransformer($store))->transform();
                return view(Parent::loadViewData($this->module.$this->view.'show'),compact('store'));
            }
            else{
                return redirect()->back()->with('danger','Store not connected to the warehouse');
            }
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
