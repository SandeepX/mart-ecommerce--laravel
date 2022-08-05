<?php

namespace App\Modules\AlpasalWarehouse\Models;

use App\Modules\Package\Models\PackageType;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Product\Models\ProductPackagingHistory;
use App\Modules\Product\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderDetail extends Model
{
    protected $table = 'warehouse_order_details';
    protected $primaryKey = 'warehouse_order_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'warehouse_order_code',
        'product_code',
        'product_variant_code',
        'package_code',
        'product_packaging_history_code',
        'is_taxable_product',
        'quantity',
        'package_quantity',
        'unit_rate',
        'mrp',
        'admin_margin_type',
        'admin_margin_value',
        'wholesale_margin_type',
        'wholesale_margin_value',
        'retail_margin_type',
        'retail_margin_value',
        'acceptance_status',
        'has_received',
        'received_quantity',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->warehouse_order_detail_code = $model->generateOrderDetailCode();
        });
    }

    public function generateOrderDetailCode()
    {
        $orderDetailPrefix = 'WOD';
        $initialIndex = '1000';
        $orderDetail = self::latest('id')->first();
        if($orderDetail){
            $codeTobePad = (int) (str_replace($orderDetailPrefix,"",$orderDetail->warehouse_order_detail_code) +1 );
           // $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestOrderDetailCode = $orderDetailPrefix.$codeTobePad;
        }else{
            $latestOrderDetailCode = $orderDetailPrefix.$initialIndex;
        }
        return $latestOrderDetailCode;
    }

    public function product(){
        return $this->belongsTo(ProductMaster::class, 'product_code', 'product_code');
    }

    public function productVariant(){
        return $this->belongsTo(ProductVariant::class, 'product_variant_code', 'product_variant_code');
    }

    public function warehousePurchaseOrder(){
        return $this->belongsTo(WarehousePurchaseOrder::class,'warehouse_order_code','warehouse_order_code');
    }

    public function warehousePurchaseOrderReceivedDetail(){
        return $this->hasOne(WarehousePurchaseOrderReceivedDetail::class,'warehouse_order_detail_code','warehouse_order_detail_code');
    }

    public function warehousePurchaseReturn(){
        return $this->hasOne(WarehousePurchaseReturn::class,'warehouse_order_detail_code','warehouse_order_detail_code');
    }

    public function productPackageType(){
        return $this->belongsTo(PackageType::class,
            'package_code','package_code');
    }

    public function productPackagingHistory(){
        return $this->belongsTo(ProductPackagingHistory::class,
            'product_packaging_history_code','product_packaging_history_code');
    }

    public function hasBeenReceived(){

        if ($this->warehousePurchaseOrderReceivedDetail){
            if($this->warehousePurchaseOrderReceivedDetail->has_received == 1){
                return true;
            }

        }
        return false;
    }

    public function getReceivedQuantity(){

        if ($this->warehousePurchaseOrderReceivedDetail){
           return $this->warehousePurchaseOrderReceivedDetail->received_quantity;
        }
    }

    public function canBeReturnedBack(){

        if ($this->hasBeenReceived() && !$this->warehousePurchaseReturn){
            return  true;
        }
        return false;
    }
}
