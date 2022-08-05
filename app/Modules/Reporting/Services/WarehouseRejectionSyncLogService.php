<?php


namespace App\Modules\Reporting\Services;

use App\Modules\Reporting\Repositories\DispatchSyncLogRepository;
use App\Modules\Reporting\Repositories\RejectionSyncLogRepository;
use Exception;

class WarehouseRejectionSyncLogService
{
    private $rejectionSyncLogRepository;
    public function __construct(
        RejectionSyncLogRepository $rejectionSyncLogRepository
    ){
        $this->rejectionSyncLogRepository = $rejectionSyncLogRepository;
    }

    public function getAllDispatchSyncLogs($paginateBy = 20){
        return $this->rejectionSyncLogRepository->getAllRejectionSyncLogs($paginateBy);
    }


}
