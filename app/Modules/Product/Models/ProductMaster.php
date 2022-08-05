<?php

namespace App\Modules\Product\Models;

use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderProduct;
use App\Modules\AlpasalWarehouse\Models\PurchaseOrderDetail;
use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Application\Traits\CheckDelete\CheckDelete;
use App\Modules\Application\Traits\IsActiveScope;
use App\Modules\Brand\Models\Brand;
use App\Modules\Cart\Models\Cart;
use App\Modules\Category\Models\CategoryMaster;
use App\Modules\Product\Helpers\ProductVariantHelper;
use App\Modules\Vendor\Models\ProductPriceList;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Store\Models\StoreOrderDetails;

class ProductMaster extends Model
{
    use SoftDeletes, CheckDelete,IsActiveScope;
    protected $table = 'products_master';
    protected $primaryKey = 'product_code';
    public $incrementing = false;
    protected $keyType = 'string';

    const PRODUCT_PER_PAGE =10;

    protected $fillable = [
        'product_code',
        'product_name',
        'slug',
        'description',
        'vendor_code',
        'brand_code',
        'category_code',
        'sensitivity_code',
       // 'warranty_code',
        'remarks',
      // 'variant_tag',
       // 'cetegory_type_code',
        'sku',
        'is_taxable',
        'is_active',
        'video_link',
        'highlights',
        'created_by',
        'updated_by',
    ];


    public $uploadFolder = 'uploads/products/';
    public $defaultNotFoundImage = 'default/images/product-default.jpg';

    public static function generateProductCode()
    {
        $productPrefix = 'P';
       // $initialIndex = '0000001';
        $initialIndex = "1000";
        $product = self::withTrashed()->latest('id')->first();
        if ($product) {
            $codeTobePad = (int) (str_replace($productPrefix, "", $product->product_code) + 1);
          //  $paddedCode = str_pad($codeTobePad, 7, '0', STR_PAD_LEFT);
            $latestProductCode = $productPrefix . $codeTobePad;
        } else {
            $latestProductCode = $productPrefix . $initialIndex;
        }
        return $latestProductCode;
    }



    public static function generateSkuCode()
    {
        $skuPrefix = 'SKU';
        $initialIndex = '1000';//

        $product = self::withTrashed()->latest('id')->first();
        if ($product) {
            $codeTobePad = (int) (str_replace($skuPrefix, "", $product->sku) + 1);
           // $codeTobePad = str_replace($skuPrefix, "", $product->sku) + 1;
            //$paddedCode = str_pad($codeTobePad, 7, '0', STR_PAD_LEFT);
            $latestSkuCode = $skuPrefix . $codeTobePad;
        } else {
            $latestSkuCode = $skuPrefix . $initialIndex;
        }
        return $latestSkuCode;
    }

    public function scopeVerified($query)
    {
        return $query->whereHas('priceList');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_code');
    }

    public function package()
    {
        return $this->hasOne(ProductPackageDetail::class, 'product_code');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_code')->withDefault();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_code');
    }

    public function category()
    {
        return $this->belongsTo(CategoryMaster::class, 'category_code');
    }

    public function sensitivity()
    {
        return $this->belongsTo(ProductSensitivity::class, 'sensitivity_code');
    }

    public function warrantyDetail()
    {
        return $this->hasOne(ProductWarrantyDetail::class, 'product_code');
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'product_code');
    }

    public function verification()
    {
        return $this->hasOne(ProductVerification::class, 'product_code', 'product_code');
    }

    public function priceList()
    {
        return $this->hasMany(ProductPriceList::class, 'product_code');
    }

    public function storeOrderDetails(){
        return $this->hasMany(StoreOrderDetails::class, 'product_code');
    }

    public function categories()
    {
        return $this->belongsToMany(
            CategoryMaster::class,
            'product_category', // table Name (Intermediate)
            'product_code',
            'category_code'
        );
    }

    public function warehouseProducts()
    {
        return $this->hasMany(WarehouseProductMaster::class, 'product_code','product_code');
    }

    public function warehousePreOrderProducts()
    {
        return $this->hasMany(WarehousePreOrderProduct::class, 'product_code','product_code');
    }

    public function warehousePurchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'product_code','product_code');
    }

    public function unitPackagingDetails(){
        return $this->hasMany(ProductUnitPackageDetail::class, 'product_code','product_code');
    }

    public function productCollections()
    {
        return $this->belongsToMany(
            ProductCollection::class,
            'product_collection_details',
            'product_code',
            'product_collection_code'
        )->withPivot([
            'is_active',
            'created_by',
            'deleted_at'
        ]);
    }

    public function getVariantInfoByProduct()
    {
        return ProductVariantHelper::getVariantInfoByProduct($this);
    }


    public function getMainVariantsInProduct()
    {
        return ProductVariantHelper::getMainVariantsInProduct($this);
    }

    public function getFeaturedImage()
    {

        $featuredImage = asset($this->defaultNotFoundImage);
        if($this->images->count()){
            $imagePath = photoToUrl($this->images[0]['image'], asset($this->uploadFolder));
            //if(fileExists($imagePath)){
                $featuredImage =  $imagePath;
           // }

        }
        return $featuredImage;

    }


    public function hasVariants()
    {
        return count($this->productVariants) > 0 ? true : false;
    }

    public function carts(){
        return $this->hasMany(Cart::class,'product_code');
    }

    public function isActive(){
        if ($this->is_active == 1){
            return true;
        }

        return false;
    }



    public function isTaxable(){
       return $this->is_taxable  == 1 ? true : false;
    }


    public function isQualifiedToDisplay(){
        if ($this->isActive() &&
            $this->vendor->isActive() &&
            count($this->priceList) >0 &&
            count($this->unitPackagingDetails) >0
        ){

            return true;
        }

        return false;
    }


    public function scopeQualifiedToDisplay($query){
        return $query->whereHas('priceList')->whereHas('vendor',function ($q){
            $q->where('vendors_detail.is_active',1);
        })->where('products_master.is_active',1)
            ->whereHas('unitPackagingDetails');
    }

    public function variantGroupDetails(){
        return $this->hasMany(ProductVariantGroup::class, 'product_code','product_code');
    }

}
