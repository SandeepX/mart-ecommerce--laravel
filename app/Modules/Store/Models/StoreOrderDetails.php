<?php

namespace App\Modules\Store\Models;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Package\Models\PackageType;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductPackageDetail;
use App\Modules\Product\Models\ProductPackagingHistory;
use App\Modules\Product\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreOrderDetails extends Model
{
    use SoftDeletes, ModelCodeGenerator;
    protected $table = 'store_order_details';

    protected $primaryKey = 'store_order_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_order_code',
        'warehouse_code',
        'product_code',
        'product_variant_code',
        'package_code',
        'product_packaging_history_code',
        'quantity',
        'unit_rate',
        'is_accepted',
        'is_taxable_product',
        'acceptance_status',
        'initial_order_quantity'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_order_detail_code = $model->generateCode();
        });
    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this,$this->primaryKey,'S0D',"1000",true);
       // return $this->generateModelCode($this, $this->primaryKey, 'SOD', '00001', 5);
    }

    public function product()
    {
        return $this->belongsTo(ProductMaster::class, 'product_code');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_code');
    }



    public function storeOrder(){
        return $this->belongsTo(StoreOrder::class,'store_order_code');
    }

    public function productPackageType(){
        return $this->belongsTo(PackageType::class,
            'package_code','package_code');
    }

    public function productPackagingHistory(){
        return $this->belongsTo(ProductPackagingHistory::class,
            'product_packaging_history_code','product_packaging_history_code');
    }

//    public function storeOrderDispatch()
//    {
//        return $this->hasOne(storeOrderDispatchDetail::class);
//    }
}
