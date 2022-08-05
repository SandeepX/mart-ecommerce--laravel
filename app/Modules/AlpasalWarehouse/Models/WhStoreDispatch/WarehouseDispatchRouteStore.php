<?php


namespace App\Modules\AlpasalWarehouse\Models\WhStoreDispatch;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseDispatchRouteStore extends Model
{
    use ModelCodeGenerator;

    protected $table = 'wh_dispatch_route_stores';

    protected $primaryKey = 'wh_dispatch_route_store_code';
    public $incrementing = false;
    protected $keyType = 'string';
    const MODEL_PREFIX="WDRSC";

    protected $fillable = [
        'wh_dispatch_route_store_code',
        'wh_dispatch_route_code',
        'store_code',
        'sort_order',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            // $authUserCode = 'U00000029';
            $model->wh_dispatch_route_code = $model->generatePrimaryCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

        static::updating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->updated_by = $authUserCode;
        });

    }

    public function generatePrimaryCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, self::MODEL_PREFIX, '1000', false);
    }

    public function warehouseDispatchRoute(){
        return $this->belongsTo(WarehouseDispatchRoute::class,'wh_dispatch_route_code','wh_dispatch_route_code');
    }

    public function store(){
        return $this->belongsTo(Store::class,'store_code','store_code');
    }

    public function warehouseDispatchRouteStoreOrders(){
        return $this->hasMany(WarehouseDispatchRouteStoreOrder::class,
            'wh_dispatch_route_store_code','wh_dispatch_route_store_code');
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','user_code');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by','user_code');
    }

    public function getFillables()
    {
        return $this->fillable;
    }
}
