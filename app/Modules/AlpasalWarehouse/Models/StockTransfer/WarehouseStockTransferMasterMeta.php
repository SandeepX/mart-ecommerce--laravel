<?php


namespace App\Modules\AlpasalWarehouse\Models\StockTransfer;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;

class WarehouseStockTransferMasterMeta extends Model
{
    use ModelCodeGenerator;
    protected $table = 'warehouse_stock_transfer_master_meta';
    protected $primaryKey = 'warehouse_stock_transfer_master_meta_code';
    public $incrementing = false;
    protected $fillable = [
        'stock_transfer_master_code',
        'key',
        'value',
        'is_active'
    ];
    const IMAGE_PATH = 'uploads/warehouse-stock-transfer/delivery-detail/';

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->warehouse_stock_transfer_master_meta_code = $model->generateWarehouseStockTransferMetaCode();
        });
    }

    public function generateWarehouseStockTransferMetaCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'WSTMM', 1000, false);
    }

    public function warehouseStockTransferMaster()
    {
        return $this->belongsTo(WarehouseStockTransfer::class, 'stock_transfer_master_code', 'stock_transfer_master_code');
    }
}