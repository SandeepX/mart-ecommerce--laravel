<?php


namespace App\Modules\Reporting\Repositories;

use App\Modules\Reporting\Models\DispatchReportSyncLog;
use App\Modules\Reporting\Models\RejectedItemReportSyncLog;
use Exception;


class RejectionSyncLogRepository
{

    public function getAllRejectionSyncLogs($paginateBy=20){
        return RejectedItemReportSyncLog::orderBy('rejected_item_report_sync_log_code','DESC')->paginate($paginateBy);
    }


}
