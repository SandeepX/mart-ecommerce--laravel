<?php


namespace App\Modules\AlpasalWarehouse\Models\WhStoreDispatch;


use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;

class WarehouseDispatchRouteStoreOrder extends Model
{
    use ModelCodeGenerator;

    protected $table = 'wh_dispatch_route_store_orders';

    protected $primaryKey = 'wh_dispatch_route_store_order_code';
    public $incrementing = false;
    protected $keyType = 'string';
    const MODEL_PREFIX="WDRSOC";

    protected $fillable = [
        'wh_dispatch_route_store_order_code',
        'wh_dispatch_route_store_code',
        'order_code',
        'order_type',
        'total_amount',
        'created_by',
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            // $authUserCode = 'U00000029';
            $model->wh_dispatch_route_store_order_code = $model->generatePrimaryCode();
            $model->created_by = $authUserCode;
        });

    }

    public function generatePrimaryCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, self::MODEL_PREFIX, '1000', false);
    }

    public function warehouseDispatchRouteStore(){
        return $this->belongsTo(WarehouseDispatchRouteStore::class,'wh_dispatch_route_store_code','wh_dispatch_route_store_code');
    }

    public function getFillables()
    {
        return $this->fillable;
    }
}
