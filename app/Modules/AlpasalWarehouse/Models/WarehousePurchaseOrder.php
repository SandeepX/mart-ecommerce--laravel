<?php

namespace App\Modules\AlpasalWarehouse\Models;

use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderPurchaseOrder;
use App\Modules\Vendor\Models\OrderReceivedByVendor;
use App\Modules\Vendor\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehousePurchaseOrder extends Model
{
    use SoftDeletes;
    protected $table = 'warehouse_orders';
    protected $primaryKey = 'warehouse_order_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'vendor_code',
        'warehouse_code',
        'order_source',
        'total_amount',
        'accepted_amount',
        'order_note',
        'order_date',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    const STATUSES=['draft','sent','processing','delivering','received'];

    public function generateOrderCode()
    {
        $orderPrefix = 'WPO';
        $initialIndex = '1000';
        $order = self::withTrashed()->latest('id')->first();
        if($order){
            $codeTobePad = (int) (str_replace($orderPrefix,"",$order->warehouse_order_code) +1 );
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
            $authUserCode = getAuthUserCode();
            $model->warehouse_order_code = $model->generateOrderCode();
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

    public function purchaseOrderDetails(){
        return $this->hasMany(PurchaseOrderDetail::class, 'warehouse_order_code','warehouse_order_code');
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class, 'warehouse_code','warehouse_code');
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class, 'vendor_code','vendor_code');
    }

    public function warehousePreOrderPurchaseOrders(){
        return $this->hasMany(WarehousePreOrderPurchaseOrder::class, 'warehouse_order_code','warehouse_order_code');
    }

    public function getOrderStatus(){
        return $this->status;
    }

  /*  public function receivedByVendor(){
        return $this->hasOne(OrderReceivedByVendor::class, 'order_code');
    }*/

}
