<?php


namespace App\Modules\SalesManager\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\SalesManager\Helpers\SalesManagerStoreLoactionsFilter;

use App\Modules\SalesManager\Services\SalesManagerService;
use Illuminate\Http\Request;
use PHPUnit\Util\Exception;
use function MongoDB\BSON\toJSON;

class ManagerStoreLocationController extends BaseController {
    public $title = 'Manager Store Locations';
    public $base_route = 'admin.salesManager.mangerStoreLocation';
    public $sub_icon = 'file';
    public $module = 'SalesManager::';
    private $view = 'admin.manager-store-locations.';

    protected $salesManagerService;
    public function __construct(SalesManagerService $salesManagerService)
    {
        $this->salesManagerService = $salesManagerService;
    }
    public function mangerStoreLocation(Request $request){
        try{
            $select =['manager_name','manager_code'];
            $salesManager = $this->salesManagerService->getAllManagersLists($select);
            $filterParam = [
                'manager_code' => $request->get('manager_code'),
                'filter_type'=>$request->get('filter_type')
            ];
            if(isset($filterParam['manager_code'])){
                $storeLocations = SalesManagerStoreLoactionsFilter::filterStoreLocation($filterParam);
            }else{
                // $filterParam['manager_code']='M1000';
                $filterParam['filter_type']='';
                $storeLocations = SalesManagerStoreLoactionsFilter::filterStoreLocation($filterParam);
            }
            return view(Parent::loadViewData($this->module . $this->view . 'index'),
                compact('salesManager','storeLocations','filterParam') );
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}
