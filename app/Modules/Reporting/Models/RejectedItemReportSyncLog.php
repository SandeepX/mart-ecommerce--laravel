<?php


namespace App\Modules\Reporting\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RejectedItemReportSyncLog extends Model
{
    use ModelCodeGenerator;

    protected $table = 'store_rejected_item_report_sync_log';
    protected $primaryKey = 'rejected_item_report_sync_log_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'rejected_item_report_sync_log_code',
        'order_type',
        'sync_started_at',
        'sync_ended_at',
        'synced_orders',
        'synced_orders_count',
        'sync_status',
        'sync_remarks'
    ];

    const SYNC_STATUS  = ['pending','success','failed'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->rejected_item_report_sync_log_code = $model->generateStoreOrderRejectedItemReportSyncLogCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateStoreOrderRejectedItemReportSyncLogCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'RIRC', '1000', false);
    }
}

