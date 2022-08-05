<?php

namespace App\Modules\Product\Models;

use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\AlpasalWarehouse\Models\PurchaseOrderDetail;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Application\Traits\CheckDelete\CheckDelete;
use App\Modules\Cart\Models\Cart;
use App\Modules\Store\Models\StoreOrder;
use App\Modules\Store\Models\StoreOrderDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes, CheckDelete;
    protected $table = 'product_variants';
    protected $primaryKey = 'product_variant_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'product_variant_code',
        'product_code',
        'product_variant_group_code',
        'product_vv_code',
        'product_variant_name',
        'sku',
        'price',
        'remarks',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $model->product_variant_code = $model->generateProductVariantCode();

        });
    }

    public static function generateProductVariantCode()
    {
        $productVariantPrefix = 'PV';
        $initialIndex = '1000';
        $productVariant = self::withTrashed()->latest('id')->first();
        if($productVariant){
            $codeTobePad = (int) (str_replace($productVariantPrefix,"",$productVariant->product_variant_code) +1) ;
          //  $paddedCode = str_pad($codeTobePad, 4, '0', STR_PAD_LEFT);
            $latestProductVariantCode = $productVariantPrefix.$codeTobePad;
        }else{
            $latestProductVariantCode = $productVariantPrefix.$initialIndex;
        }
        return $latestProductVariantCode;
    }

    public function isVerified(){
        return isset($this->price) ? true : false;
    }

    public function details(){
        return $this->hasMany(ProductVariantDetail::class, 'product_variant_code');
    }

    public function images(){
        return $this->hasMany(ProductVariantImage::class, 'product_variant_code');

    }

    public function product(){
        return $this->belongsTo(ProductMaster::class, 'product_code');
    }

    public function price(){
       return $this->hasOne(ProductPriceList::class,'product_variant_code');
    }

    public function carts(){
        return $this->hasMany(Cart::class, 'product_variant_code');
    }

    public function storeOrderDetails(){
        return $this->hasMany(StoreOrderDetails::class, 'product_variant_code');
    }


    public function warehouseProducts()
    {
        return $this->hasMany(WarehouseProductMaster::class, 'product_variant_code','product_variant_code');
    }

    public function warehousePreOrderProducts()
    {
        return $this->hasMany(WarehousePreOrderProduct::class, 'product_variant_code','product_variant_code');
    }

    public function warehousePurchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'product_variant_code','product_variant_code');
    }

    public function unitPackagingDetail(){
        return $this->hasOne(ProductUnitPackageDetail::class, 'product_variant_code','product_variant_code');
    }
    // public function priceList()
    // {
    //     return $this->hasOne(ProductPriceList::class, 'product_variant_code');
    // }

}
