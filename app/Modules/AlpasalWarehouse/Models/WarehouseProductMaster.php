<?php


namespace App\Modules\AlpasalWarehouse\Models;


use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseStockTransferDetail;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductVariant;
use App\Modules\User\Models\User;
use App\Modules\Vendor\Models\ProductPriceList;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Database\Eloquent\Model;

use Exception;
class WarehouseProductMaster extends Model
{
    protected $table = 'warehouse_product_master';
    protected $primaryKey = 'warehouse_product_master_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'warehouse_code',
        'product_code',
        'product_variant_code',
        'vendor_code',
        'is_active',
        'min_order_quantity',
        'max_order_quantity',
        'current_stock',
        'created_by',
        'updated_by',
    ];

    public function generateCode()
    {
        $prefix = 'WPM';
        $initialIndex = '1000';
        $warehouseProductMaster = self::latest('id')->first();
        if($warehouseProductMaster){
            $codeTobePad = (int) (str_replace($prefix,"",$warehouseProductMaster->warehouse_product_master_code) +1 );
            //$paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestCode = $prefix.$codeTobePad;
        }else{
            $latestCode = $prefix.$initialIndex;
        }
        return $latestCode;
    }

    public function getProductCode()
    {
        $products = WarehouseProductMaster::get();
        foreach ($products as $value)
        {
            $data[]=$value->product_code;
        }
        return $data;
    }
    public function getProduct(array $productcode)
    {
        $products = ProductMaster::whereIn('product_code',$productcode)->get();

        return $products;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $authUserCode = getAuthUserCode();
            $model->warehouse_product_master_code = $model->generateCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });

        static::updating(function ($model) {
            //$authUserCode = getAuthUserCode();
           // $model->updated_by = $authUserCode;
        });
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_code','warehouse_code');
    }

    public function product(){
        return $this->belongsTo(ProductMaster::class, 'product_code', 'product_code');
    }

    public function productVariant(){
        return $this->belongsTo(ProductVariant::class, 'product_variant_code', 'product_variant_code');
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class, 'vendor_code', 'vendor_code');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by', 'user_code');
    }

    public function warehouseProductStock(){
        return $this->hasMany(WarehouseProductStock::class, 'warehouse_product_master_code ', 'warehouse_product_master_code ');
    }

    public function warehouseProductStockView(){
        return $this->hasOne(WarehouseProductStockView::class,'code','warehouse_product_master_code');
    }

    public function warehouseProductPriceMaster(){
        return $this->hasOne(WarehouseProductPriceMaster::class,'warehouse_product_master_code','warehouse_product_master_code');
    }

    public function warehouseProductPriceHistories(){
        return $this->hasMany(WarehouseProductPriceHistory::class,'warehouse_product_master_code',
            'warehouse_product_master_code');
    }

    public function packagingUnitDisableList(){
        return $this->hasMany(WarehouseProductPackagingUnitDisableList::class,
            'warehouse_product_master_code','warehouse_product_master_code');
    }

    public function scopeQualifiedToDisplay($query){
        return $query->whereHas('warehouseProductPriceMaster')
            ->where('warehouse_product_master.is_active',1);
    }

    public function isActiveProduct(){
        if ($this->is_active == 1){
            return true;
        }
        return false;
    }

    public function getCurrentProductStock(){
//        if ($this->warehouseProductStockView){
//            return $this->warehouseProductStockView->current_stock;
//        }
       return $this->current_stock;
    }

    //from ProductMaster model
    public function getProductProperty($propertyName){

        if($this->product){

            switch ($propertyName){

                case 'product_name' :
                    return $this->product->product_name;
                    break;
                case 'product_code':
                    return $this->product->product_code;
                    break;
                case 'brand_name':
                    return $this->product->brand->brand_name;
                    break;
                case 'category_name':
                    return $this->product->category->category_name;
                    break;
                case 'vendor_name':
                    return $this->product->vendor->vendor_name;
                    break;
                case 'price_list':
                    return $this->product->priceList;
                    break;
                default:
                    throw new Exception('Property name not found');
            }
        }
    }
    public function scopeVerified($query)
    {
        return $query->whereHas('priceList');
    }
    public function priceList()
    {
        return $this->hasMany(ProductPriceList::class, 'product_code');
    }
    public function isActive(){
        if ($this->is_active == 1){
            return true;
        }

        return false;
    }
//    public function warehouseProductCollections()
//    {
//        return $this->belongsToMany(WarehouseProductCollection::class,
//            'wh_product_collection_details',
//            'product_code',
//            'product_collection_code'
//        )->withPivot([
//            'is_active',
//            'created_by',
//            'deleted_at'
//        ]);
//    }

    public function currentstock(){
       return $this->hasOne(WarehouseProductStockView::class,'code','warehouse_product_master_code');
    }

    public function warehouseStockTransferDetails()
    {
        return $this->belongsToMany(WarehouseStockTransferDetail::class, 'warehouse_product_master_code', 'warehouse_product_master_code');
    }


}
