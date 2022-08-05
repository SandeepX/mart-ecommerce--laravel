<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\PreOrder;


use App\Modules\Application\Controllers\BaseController;
use App\Modules\Store\Helpers\PreOrder\StorePreOrderHelper;

use Exception;
use Illuminate\Http\Request;

class StoreHavingPreOrderController extends BaseController
{
    public $title = 'Stores PreOrder';
    public $base_route = 'warehouse.warehouse-pre-orders.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.warehouse-pre-orders.stores.';

    public function __construct()
    {
        $this->middleware('permission:View Store Pre Orders In Pre Order', ['only' => [
            'getStoresHavingPreOrders',
            'getStorePreOrdersListing'
        ]]);
    }

    public function getStoresHavingPreOrders(Request $request){
        try{
            $filterParameters=[
                'store_name' => $request->store_name,
                'statuses' =>$request->status
            ];

            $preOrderStatuses =['pending','finalized','processing','dispatched','cancelled'];
            $stores = StorePreOrderHelper::getStoreParticipantsInPreOrder($filterParameters,20);
            return view($this->loadViewData($this->module . $this->view . 'index'),
                compact('stores','preOrderStatuses','filterParameters'));

        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }

    }
    public function getStorePreOrdersListing(Request $request,$storeCode){
        try{
            $filterParameters=[
                'pre_order_name' => $request->pre_order_name,
                'statuses' =>$request->status,
                'payment_status' =>$request->payment_status,
                'start_time' => $request->get('start_time'),
                'end_time' => $request->get('end_time'),
            ];

            $preOrderStatuses =['pending','finalized','dispatched','cancelled'];
            $preOrdersListing = StorePreOrderHelper::getPreOrdersMadeByStoreCode(
                $storeCode,$filterParameters,20);
            return view($this->loadViewData($this->module . $this->view . 'store-preorders'),
                compact('preOrdersListing','preOrderStatuses','filterParameters','storeCode'));

        }catch (Exception $exception){
            return redirect()->route('warehouse.warehouse-pre-orders.stores')->with('danger', $exception->getMessage());
        }
    }
}
