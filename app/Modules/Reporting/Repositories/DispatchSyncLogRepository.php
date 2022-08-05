<?php


namespace App\Modules\Reporting\Repositories;

use App\Modules\Reporting\Models\DispatchReportSyncLog;
use Exception;


class DispatchSyncLogRepository
{

    public function getAllDispatchSyncLogs($paginateBy=20){
        return DispatchReportSyncLog::orderBy('dispatch_report_sync_log_code','DESC')->paginate($paginateBy);
    }


}
