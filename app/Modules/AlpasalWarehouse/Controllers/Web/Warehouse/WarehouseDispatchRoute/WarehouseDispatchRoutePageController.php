<?php


namespace App\Modules\AlpasalWarehouse\Controllers\Web\Warehouse\WarehouseDispatchRoute;


use App\Modules\Application\Controllers\BaseController;

use Exception;

class WarehouseDispatchRoutePageController extends  BaseController
{
    public $title = 'Alpasal Warehouse Dispatch Route';
    public $base_route = 'warehouse.dispatch-route.';
    public $sub_icon = 'file';
    public $module = 'AlpasalWarehouse::';

    private $view='warehouse.warehouse-dispatch-route.';

    public function showPage($dispatchRouteCode){
        try{

            return view($this->loadViewData($this->module.$this->view.'show'),
                compact('dispatchRouteCode'));
        }catch (Exception $exception){
            return redirect()->route('warehouse.dashboard')->with('danger',$exception->getMessage());
        }
    }

    public function getDispatchRoutesLists(){

        try {
            return view($this->loadViewData($this->module.$this->view.'lists'));
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }

    }
}
