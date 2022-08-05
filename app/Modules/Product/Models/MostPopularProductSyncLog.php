<?php

namespace App\Modules\Product\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MostPopularProductSyncLog extends Model
{
    use ModelCodeGenerator;
    protected $table = 'most_popular_products_sync_log';
    protected $primaryKey = 'most_popular_products_sync_log_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'most_popular_products_sync_log_code',
        'sync_started_at',
        'sync_ended_at',
        'sync_status',
        'sync_remarks'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->most_popular_products_sync_log_code = $model->generateMostPopularProductsSyncLogCode();
        });
        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateMostPopularProductsSyncLogCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'MPPSLC', '1000', false);
    }
}
