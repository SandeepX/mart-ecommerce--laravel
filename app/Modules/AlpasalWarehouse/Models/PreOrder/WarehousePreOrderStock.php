<?php


namespace App\Modules\AlpasalWarehouse\Models\PreOrder;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductStock;
use Illuminate\Database\Eloquent\Model;

class WarehousePreOrderStock extends Model
{
    protected $table = 'warehouse_preorder_stock';
    protected $primaryKey = 'warehouse_preorder_stock_code ';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'warehouse_product_stock_code',
        'warehouse_preorder_listing_code',
        'store_preorder_detail_code',
    ];

    public function generateCode()
    {
        $prefix = 'WPRS';
        $initialIndex = '1000';
        $stock = self::latest('id')->first();
        if($stock){
            $codeTobePad = (int) (str_replace($prefix,"",$stock->warehouse_preorder_stock_code) +1 );
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
            $model->warehouse_preorder_stock_code  = $model->generateCode();
        });
    }

    public function warehouseProductStock(){
        return $this->belongsTo(WarehouseProductStock::class,'warehouse_product_stock_code','warehouse_product_stock_code');
    }

    public function warehousePreOrderListing(){
        return $this->belongsTo(WarehousePreOrderListing::class,'warehouse_preorder_listing_code','warehouse_preorder_listing_code');
    }
}
