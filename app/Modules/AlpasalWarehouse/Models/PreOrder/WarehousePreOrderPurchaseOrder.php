<?php


namespace App\Modules\AlpasalWarehouse\Models\PreOrder;


use App\Modules\AlpasalWarehouse\Models\WarehousePurchaseOrder;
use Illuminate\Database\Eloquent\Model;

class WarehousePreOrderPurchaseOrder extends Model
{
    protected $table = 'warehouse_preorder_purchase_orders';
    protected $primaryKey = 'warehouse_preorder_purchase_order_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'warehouse_order_code',
        'warehouse_preorder_listing_code',
    ];

    public function generateOrderCode()
    {
        $orderPrefix = 'WPPO';
        $initialIndex = '1000';
        $order = self::latest('id')->first();
        if($order){
            $codeTobePad = (int) (str_replace($orderPrefix,"",$order->warehouse_preorder_purchase_order_code) +1);
            //  $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
            $latestOrderCode = $orderPrefix.$codeTobePad;
        }else{
            $latestOrderCode = $orderPrefix.$initialIndex;
        }
        return $latestOrderCode;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->warehouse_preorder_purchase_order_code = $model->generateOrderCode();
        });

    }

    public function warehousePreOrderListing(){
        return $this->belongsTo(WarehousePreOrderListing::class, 'warehouse_preorder_listing_code','warehouse_preorder_listing_code');
    }

    public function warehouseOrder(){
        return $this->belongsTo(WarehousePurchaseOrder::class, 'warehouse_order_code','warehouse_order_code');
    }
}
