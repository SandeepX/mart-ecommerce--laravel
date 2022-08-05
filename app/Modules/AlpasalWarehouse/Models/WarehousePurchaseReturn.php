<?php


namespace App\Modules\AlpasalWarehouse\Models;


use App\Modules\Vendor\Models\Vendor;
use Illuminate\Database\Eloquent\Model;

class WarehousePurchaseReturn extends Model
{
    protected $table = 'warehouse_purchase_return';
    protected $primaryKey = 'warehouse_purchase_return_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'warehouse_order_code',
        'vendor_code',
        'warehouse_order_detail_code',
        'return_quantity',
        'accepted_return_quantity',
        'status',
        'reason_type',
        'return_reason_remarks',
        'status_remarks',
        'status_responded_by',
        'status_responded_at',
        'created_by',
        'updated_by'
    ];

    const STATUSES=['pending','accepted','rejected'];
    const REASON_TYPES=['damaged','expired'];

    public function generateCode()
    {
        $prefix = 'WPR';
        $initialIndex = '1000';
        $warehousePurchaseReturn = self::latest('id')->first();
        if($warehousePurchaseReturn){
            $codeTobePad = (int) (str_replace($prefix,"",$warehousePurchaseReturn->warehouse_purchase_return_code) +1 );
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
            $model->warehouse_purchase_return_code = $model->generateCode();
            $model->created_by = $authUserCode;
            $model->updated_by = $authUserCode;
        });
    }

    public function vendor(){
        return $this->belongsTo(Vendor::class,'vendor_code','vendor_code');
    }

    public function warehouseOrder(){
        return $this->belongsTo(WarehousePurchaseOrder::class,'warehouse_order_code','warehouse_order_code');
    }

    public function warehouseOrderDetail(){
        return $this->belongsTo(PurchaseOrderDetail::class,'warehouse_order_detail_code','warehouse_order_detail_code');
    }
}
