<?php


namespace App\Modules\AlpasalWarehouse\Models\StockTransfer;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;

class WarehouseTransferLoss extends Model
{
    use ModelCodeGenerator;
    protected $table = 'warehouse_transfer_loss_master';
    protected $primaryKey = 'warehouse_stock_transfer_loss_master_code';
    public $incrementing = false;
    protected $fillable = [
        'stock_transfer_master_code',
        'warehouse_product_master_code',
        'quantity',
        'reason',
        'package_quantity',
        'package_code',
        'product_packaging_history_code'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->warehouse_stock_transfer_loss_master_code = $model->generateWarehouseTransferLossCode();
        });
    }

    public function generateWarehouseTransferLossCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'WTL', 1000, false);
    }

    public function warehouseStockTransferMaster()
    {
        return $this->belongsTo(WarehouseStockTransfer::class, 'stock_transfer_master_code', 'stock_transfer_master_code');
    }
}
