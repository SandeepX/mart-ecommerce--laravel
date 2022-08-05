<?php

namespace App\Modules\Reporting\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Reporting\Models\DispatchReportSyncLog;
use App\Modules\Reporting\Services\WarehouseDispatchSyncLogService;
use Exception;


class WarehouseDispatchSyncLogsController extends BaseController
{
    public $title = 'Warehouse Dispatch Sync Logs';
    public $base_route = 'admin.wh-dispatch-sync-logs';
    public $sub_icon = 'file';
    public $module = 'Reporting::';

    private $view;
    private $warehouseDispatchSyncLogService;

    public function __construct(
        WarehouseDispatchSyncLogService $warehouseDispatchSyncLogService
    ){
        $this->warehouseDispatchSyncLogService = $warehouseDispatchSyncLogService;
        $this->view = 'admin.wh-sync-logs.wh-dispatch-logs.';
    }

    public function index(){
         try{
             $dispatchSyncLogsLists = $this->warehouseDispatchSyncLogService->getAllDispatchSyncLogs(20);
             return view( Parent::loadViewData($this->module.$this->view.'index'),
                 compact('dispatchSyncLogsLists'))->render();
         }catch (Exception $exception){
             return redirect()->back()->with('danger', $exception->getMessage());
         }
    }


}
