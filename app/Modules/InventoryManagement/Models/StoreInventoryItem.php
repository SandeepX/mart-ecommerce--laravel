<?php


namespace App\Modules\InventoryManagement\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Package\Models\PackageType;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductVariant;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StoreInventoryItem extends Model
{
    use ModelCodeGenerator;

    protected $table = 'store_inventory_item_detail';
    protected $primaryKey = 'siid_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_inventory_code',
        'cost_price',
        'mrp',
        'manufacture_date',
        'signature',
        'expiry_date',
        'created_by',
        'updated_by'

    ];

    const RECORDS_PER_PAGE = 10;


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = getAuthUserCode();
            $model->updated_by = getAuthUserCode();
            $model->siid_code = $model->generateStoreInventoryItemDetailCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
            $model->updated_at = Carbon::now();
        });
    }

    public function generateStoreInventoryItemDetailCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SIID', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }


    public function storeInventory()
    {
        return $this->belongsTo(StoreInventory::class, 'store_inventory_code', 'store_inventory_code');
    }

    public function storeInventoryItemRecievedQty()
    {
        return $this->hasMany(StoreInventoryItemReceivingQty::class,'siid_code','siid_code');
    }

}



