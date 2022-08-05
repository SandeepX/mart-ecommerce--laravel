<?php


namespace App\Modules\Reporting\Services;

use App\Modules\Reporting\Repositories\DispatchSyncLogRepository;
use Exception;


class WarehouseDispatchSyncLogService
{
    private $dispatchSyncLogRepository;
    public function __construct(
        DispatchSyncLogRepository $dispatchSyncLogRepository
    ){
        $this->dispatchSyncLogRepository = $dispatchSyncLogRepository;
    }

    public function getAllDispatchSyncLogs($paginateBy = 20){
        return $this->dispatchSyncLogRepository->getAllDispatchSyncLogs($paginateBy);
    }


}
