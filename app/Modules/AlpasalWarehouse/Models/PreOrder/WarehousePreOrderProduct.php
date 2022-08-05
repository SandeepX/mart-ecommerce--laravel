<?php


namespace App\Modules\AlpasalWarehouse\Models\PreOrder;


use App\Modules\Application\Traits\IsActiveScope;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductVariant;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehousePreOrderProduct extends Model
{

    use SoftDeletes,IsActiveScope;

    protected $table = 'warehouse_preorder_products';
    protected $primaryKey = 'warehouse_preorder_product_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'warehouse_preorder_listing_code',
        'product_code',
        'product_variant_code',
        'mrp',
        'admin_margin_type',
        'admin_margin_value',
        'wholesale_margin_type',
        'wholesale_margin_value',
        'retail_margin_type',
        'retail_margin_value',
        'min_order_quantity',
        'max_order_quantity',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function generateCode()
    {
        $prefix = 'WPPC';
        $initialIndex = '1000';
        $preOrderProduct = self::withTrashed()->latest('id')->first();
        if($preOrderProduct){
            $codeTobePad = (int) (str_replace($prefix,"",$preOrderProduct->warehouse_preorder_product_code) +1 );
            //$paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$codeTobePad;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->warehouse_preorder_product_code = $model->generateCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

        static::updating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->updated_by = $authUserCode;
        });

        static::deleting(function ($model) {
            $model->deleted_by = getAuthUserCode();
            $model->save();
        });
    }

    public function warehousePreOrderListing(){
        return $this->belongsTo(WarehousePreOrderListing::class,'warehouse_preorder_listing_code','warehouse_preorder_listing_code');
    }
    public function product(){
        return $this->belongsTo(ProductMaster::class,'product_code','product_code');
    }
    public function productVariant(){
        return $this->belongsTo(ProductVariant::class,'product_variant_code','product_variant_code');
    }
    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','user_code');
    }
    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by','user_code');
    }

    public function storePreOrderDetails(){
        return $this->hasMany(StorePreOrderDetail::class,'warehouse_preorder_product_code','warehouse_preorder_product_code');
    }

    public function packagingDisableList(){
        return $this->hasMany(PreOrderPackagingUnitDisableList::class,'warehouse_preorder_product_code','warehouse_preorder_product_code');
    }

    public function isPriceDisplayable(){

        if ($this->warehousePreOrderListing){
          return $this->warehousePreOrderListing->isDisplayable();
        }
        return false;
    }

    public function hasBeenOrderedByStore(){
        if (count($this->storePreOrderDetails) > 0){
            return true;
        }
        return false;
    }
}
