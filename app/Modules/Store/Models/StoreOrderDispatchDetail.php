<?php
namespace App\Modules\Store\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreOrderDispatchDetail extends Model
{
    use ModelCodeGenerator;

    protected $table = 'store_order_dispatch_details';
    protected $primaryKey = 'store_order_dispatch_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_order_dispatch_detail_code',
        'store_order_code',
        'driver_name',
        'vehicle_type',
        'vehicle_number',
        'contact_number',
        'expected_delivery_time',
        'created_by'

    ];

    const RECORDS_PER_PAGE = 10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_order_dispatch_detail_code = $model->generateStoreOrderDispatchDetailCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateStoreOrderDispatchDetailCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SODD', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function storeOrderCode()
    {
        return $this->belongsTo(StoreOrder::class, 'store_order_code', 'store_order_code');
    }

}








