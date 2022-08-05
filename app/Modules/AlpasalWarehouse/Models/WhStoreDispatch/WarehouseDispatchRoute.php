<?php


namespace App\Modules\AlpasalWarehouse\Models\WhStoreDispatch;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\AlpasalWarehouse\Models\WhStoreGroup\WarehouseStoreGroupDetail;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class WarehouseDispatchRoute extends Model
{
    use ModelCodeGenerator;

    protected $table = 'warehouse_dispatch_routes';

    protected $primaryKey = 'wh_dispatch_route_code';
    public $incrementing = false;
    protected $keyType = 'string';
    const MODEL_PREFIX="WDRC";

    protected $fillable = [
        'wh_dispatch_route_code',
        'warehouse_code',
        'route_name',
        'vehicle_name',
        'vehicle_number',
        'driver_name',
        'driver_license_number',
        'driver_contact_primary',
        'driver_contact_secondary',
        'description',
        'status',
        'question_checked_meta',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    const STATUSES =['pending','dispatched'];
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

    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_code','warehouse_code');
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','user_code');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by','user_code');
    }

    public function warehouseDispatchRouteStores(){
        return $this->hasMany(WarehouseDispatchRouteStore::class,'wh_dispatch_route_code','wh_dispatch_route_code');
    }

    public function maxSortedWarehouseDispatchRouteStore(){
        return $this->hasOne(WarehouseDispatchRouteStore::class,'wh_dispatch_route_code','wh_dispatch_route_code')
            ->select(
                [
                    'wh_dispatch_route_store_code',
                    'wh_dispatch_route_code'
                ]
            )->addSelect(DB::raw("MAX(sort_order) AS max_sort_order"));
    }

    public function warehouseDispatchRouteMarkers(){
        return $this->hasMany(WarehouseDispatchRouteMarker::class,'wh_dispatch_route_code','wh_dispatch_route_code');
    }

    public function isDispatched(){
        if ($this->status == 'dispatched'){
            return true;
        }
        return false;
    }
}
