<?php


namespace App\Modules\AlpasalWarehouse\Models\WhStoreDispatch;


use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;

class WarehouseDispatchRouteMarker extends Model
{
    use ModelCodeGenerator;

    protected $table = 'wh_dispatch_route_markers';

    protected $primaryKey = 'wh_dispatch_route_marker_code';
    public $incrementing = false;
    protected $keyType = 'string';
    const MODEL_PREFIX="WDRMC";

    protected $fillable = [
        'wh_dispatch_route_marker_code',
        'wh_dispatch_route_code',
        'latitude',
        'longitude',
        'sort_order',
        'is_store',
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
            $model->wh_dispatch_route_marker_code = $model->generatePrimaryCode();
            $model->created_by = $authUserCode;
        });

    }

    public function generatePrimaryCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, self::MODEL_PREFIX, '1000', false);
    }

    public function warehouseDispatchRoute(){
        return $this->belongsTo(WarehouseDispatchRoute::class,'wh_dispatch_route_code','wh_dispatch_route_code');
    }

    public function getFillables(){
        return $this->fillable;
    }
}
