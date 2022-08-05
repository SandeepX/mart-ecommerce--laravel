<?php


namespace App\Modules\AlpasalWarehouse\Models\StockTransfer;

use App\Modules\AlpasalWarehouse\Models\WarehouseProductStock;
use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseTransferStock extends Model
{
    use SoftDeletes, ModelCodeGenerator;
    protected $table = 'warehouse_transfer_stocks';
    protected $primaryKey = 'warehouse_transfer_stock_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'warehouse_product_stock_code',
        'stock_transfer_master_code'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->warehouse_transfer_stock_code = $model->generateWarehouseTransferStockCode();
        });
    }

    public function generateWarehouseTransferStockCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'WTS', 1000, true);
    }

    public function warehouseProductStock()
    {
        return $this->belongsTo(WarehouseProductStock::class, 'warehouse_product_stock_code', 'warehouse_product_stock_code');
    }

    public function warehouseStockTransfer()
    {
        return $this->belongsTo(WarehouseStockTransfer::class, 'stock_transfer_master_code', 'stock_transfer_master_code');
    }

}