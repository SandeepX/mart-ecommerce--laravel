<?php


namespace App\Modules\AlpasalWarehouse\Models;

use Illuminate\Database\Eloquent\Model;

class WarehousePurchaseOrderReceivedDetail extends Model
{

    protected $table = 'warehouse_purchase_order_received_details';
    protected $primaryKey = 'warehouse_purchase_order_received_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'warehouse_order_code',
        'product_code',
        'product_variant_code',
        'package_quantity',
        'package_code',
        'product_packaging_history_code',
        'has_received',
        'received_quantity',
        'manufactured_date',
        'expiry_date',
    ];

    public function generateCode()
    {
        $prefix = 'WPORD';
        $initialIndex = '1000';
        $warehouseReceivedDetail = self::latest('id')->first();
        if($warehouseReceivedDetail){
            $codeTobePad = (int) (str_replace($prefix,"",$warehouseReceivedDetail->warehouse_purchase_order_received_detail_code) +1 );
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
            $model->warehouse_purchase_order_received_detail_code = $model->generateCode();
        });

    }

    public function warehousePurchaseOrder(){
        return $this->belongsTo(WarehousePurchaseOrder::class,'warehouse_order_code','warehouse_order_code');
    }

}
