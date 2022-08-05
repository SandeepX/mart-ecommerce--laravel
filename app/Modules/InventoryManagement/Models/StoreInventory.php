<?php


namespace App\Modules\InventoryManagement\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductVariant;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StoreInventory extends Model
{
    use ModelCodeGenerator;

    protected $table = 'store_inventories';
    protected $primaryKey = 'store_inventory_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_code',
        'vendor_name',
        'product_code',
        'product_variant_code',
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
            $model->store_inventory_code = $model->generateStoreInventoryCode();
        });

        static::updating(function ($model) {
            $model->updated_by = getAuthUserCode();
            $model->updated_at = Carbon::now();
        });
    }

    public function generateStoreInventoryCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SI', '1000', false);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }

    public function storeDetail()
    {
        return $this->belongsTo(Store::class, 'store_code', 'store_code');
    }

    public function productDetail()
    {
        return $this->belongsTo(ProductMaster::class, 'product_code', 'product_code');
    }

    public function productVariantDetail()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_code', 'product_variant_code');
    }

    public function storeInventoryItem()
    {
        return $this->hasMany(StoreInventoryItem::class,'store_inventory_code','store_inventory_code');
    }

}



