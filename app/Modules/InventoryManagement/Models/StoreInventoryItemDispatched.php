<?php


namespace App\Modules\InventoryManagement\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Package\Models\PackageType;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreInventoryItemDispatched extends Model
{
    use SoftDeletes;

    use ModelCodeGenerator;

    protected $table = 'store_inventory_item_dispatched_qty_detail';
    protected $primaryKey = 'siidqd_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'siid_code',
        'pph_code',
        'package_code',
        'quantity',
        'micro_unit_quantity',
        'payment_type',
        'selling_price',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    const RECORDS_PER_PAGE = 10;
    const PAYMENT_TYPE = ['credit','bank','cash'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
            $model->siidqd_code = $model->generateStoreInventoryItemDispatchedQtyCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
            $model->updated_at = Carbon::now();
        });

        static::deleting(function ($model){
            $model->deleted_by = getAuthUserCode();
        });
    }

    public function generateStoreInventoryItemDispatchedQtyCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SIIDQD', '1000', true);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }

    public function packageTypeDetail()
    {
        return $this->belongsTo(PackageType::class, 'package_code', 'package_code');
    }

    public function storeInventoryItemDetail()
    {
        return $this->belongsTo(StoreInventoryItem::class, 'siid_code', 'siid_code');
    }

}




