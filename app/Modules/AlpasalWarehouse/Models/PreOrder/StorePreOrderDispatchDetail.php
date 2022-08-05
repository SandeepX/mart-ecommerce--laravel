<?php


namespace App\Modules\AlpasalWarehouse\Models\PreOrder;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StorePreOrderDispatchDetail extends Model
{
    use ModelCodeGenerator;
    protected $table = 'store_pre_order_dispatch_details';
    protected $primaryKey = 'store_pre_order_dispatch_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'store_preorder_code',
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
            $model->store_pre_order_dispatch_detail_code = $model->generateStorePreOrderDispatchDetailCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateStorePreOrderDispatchDetailCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SPODD', 1000, false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function storePreOrderCode()
    {
        return $this->belongsTo(StorePreOrder::class, 'store_preorder_code', 'store_preorder_code');
    }

}
