<?php


namespace App\Modules\AlpasalWarehouse\Models\WhStoreGroup;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class WarehouseStoreGroupDetail extends Model
{
    use ModelCodeGenerator;

    protected $table = 'warehouse_store_group_details';

    protected $primaryKey = 'wh_store_group_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';
    const MODEL_PREFIX="WSGDC";

    protected $fillable = [
        'wh_store_group_detail_code',
        'wh_store_group_code',
        'store_code',
        'sort_order',
        'is_active',
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
            $model->wh_store_group_detail_code = $model->generatePrimaryCode();
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

    public function warehouseStoreGroup(){
        return $this->belongsTo(WarehouseStoreGroup::class,'wh_store_group_code','wh_store_group_code');
    }

    public function store(){
        return $this->belongsTo(Store::class,'store_code','store_code');
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
