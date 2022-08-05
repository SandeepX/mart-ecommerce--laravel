<?php

namespace App\Modules\Reporting\Controllers\Web\Admin;

use App\Modules\Application\Controllers\BaseController;
use App\Modules\Reporting\Models\DispatchReportSyncLog;
use App\Modules\Reporting\Services\WarehouseDispatchSyncLogService;
use App\Modules\Reporting\Services\WarehouseRejectionSyncLogService;
use Exception;


class WarehouseRejectionSyncLogsController extends BaseController
{
    public $title = 'Warehouse Rejection Sync Logs';
    public $base_route = 'admin.wh-rejection-sync-logs';
    public $sub_icon = 'file';
    public $module = 'Reporting::';

    private $view;
    private $ware;

    public function __construct(
        WarehouseRejectionSyncLogService $warehouseRejectionSyncLogService
    ){
        $this->warehouseRejectionSyncLogService = $warehouseRejectionSyncLogService;
        $this->view = 'admin.wh-sync-logs.wh-rejection-logs.';
    }

    public function index(){
        try{
            $rejectionSyncLogsLists = $this->warehouseRejectionSyncLogService->getAllDispatchSyncLogs(20);
            return view( Parent::loadViewData($this->module.$this->view.'index'),
                compact('rejectionSyncLogsLists'))->render();
        }catch (Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }


}
