<?php


namespace App\Modules\Reporting\Helpers;

use App\Modules\Reporting\Models\RejectedItemReportSyncLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RejectedItemSyncLogHelper
{

    public static function getLastRejectedItemSyncDateAndStatus()
    {
        $normalOrderLastSync = RejectedItemReportSyncLog::where('order_type', 'normal_order')->orderBy('id', 'DESC')->first();
        $preOrderLastSync = RejectedItemReportSyncLog::where('order_type', 'preorder')->orderBy('id', 'DESC')->first();
        $normalOrderSyncDate = NUll;
        $normalOrderSyncStatus = NULL;
        $normalOrderCount = NULL;
        $preOrderSyncDate = NULL;
        $preOrderSyncStatus = NULL;
        $preOrderCount = NULL;
        if($normalOrderLastSync){
            $normalOrderSyncDate = getReadableDate(getNepTimeZoneDateTime($normalOrderLastSync->sync_started_at));
            $normalOrderSyncStatus = $normalOrderLastSync->sync_status;
            $normalOrderCount = $normalOrderLastSync->synced_orders_count;
        }
        if($preOrderLastSync){
            $preOrderSyncDate = getReadableDate(getNepTimeZoneDateTime($preOrderLastSync->sync_started_at));
            $preOrderSyncStatus = $preOrderLastSync->sync_status;
            $preOrderCount = $preOrderLastSync->synced_orders_count;
        }
        return [
            'normalOrder'=>[
                'date' =>$normalOrderSyncDate,
                'status'=>ucwords($normalOrderSyncStatus),
                'count' => $normalOrderCount
            ],
            'preOrder'=>[
                'date' => $preOrderSyncDate,
                'status' => ucwords($preOrderSyncStatus),
                'count' => $preOrderCount
            ]
        ];

    }

}

