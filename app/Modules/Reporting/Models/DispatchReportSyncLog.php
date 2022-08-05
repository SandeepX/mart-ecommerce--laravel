<?php

namespace App\Modules\Reporting\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DispatchReportSyncLog extends Model
{
    use ModelCodeGenerator;
    protected $table = 'dispatch_report_sync_log';
    protected $primaryKey = 'dispatch_report_sync_log_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'dispatch_report_sync_log_code',
        'order_type',
        'sync_started_at',
        'sync_ended_at',
        'synced_orders',
        'synced_orders_count',
        'sync_status',
        'sync_remarks'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->dispatch_report_sync_log_code = $model->generateDispatchReportSyncLogCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateDispatchReportSyncLogCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'DRSLC', '1000', false);
    }
}
