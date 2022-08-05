<?php


namespace App\Modules\AlpasalWarehouse\Models\WhStoreGroup;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class WarehouseStoreGroup extends Model
{
    use ModelCodeGenerator;
    use SoftDeletes;

    protected $table = 'warehouse_store_groups';

    protected $primaryKey = 'wh_store_group_code';
    public $incrementing = false;
    protected $keyType = 'string';
    const MODEL_PREFIX="WSGC";

    protected $fillable = [
        'wh_store_group_code',
        'warehouse_code',
        'name',
        'description',
        'group_basis',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            // $authUserCode = 'U00000029';
            $model->wh_store_group_code = $model->generatePrimaryCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

         static::updating(function ($model) {
             $authUserCode = getAuthUserCode();
             $model->updated_by = $authUserCode;
         });

        static::deleting(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->deleted_by = $authUserCode;
            $model->save();
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

    public function warehouseStoreGroupDetails(){
        return $this->hasMany(WarehouseStoreGroupDetail::class,'wh_store_group_code','wh_store_group_code');
    }

    public function maxSortedWarehouseStoreGroupDetail(){
        return $this->hasOne(WarehouseStoreGroupDetail::class,'wh_store_group_code','wh_store_group_code')
            ->select(
                 [
                     'wh_store_group_detail_code',
                     'wh_store_group_code'
                 ]
             )->addSelect(DB::raw("MAX(sort_order) AS max_sort_order"));
    }


    public function getFillables()
    {
        return $this->fillable;
    }
}
