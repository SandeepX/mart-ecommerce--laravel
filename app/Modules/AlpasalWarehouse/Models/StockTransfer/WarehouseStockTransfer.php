<?php


namespace App\Modules\AlpasalWarehouse\Models\StockTransfer;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseStockTransfer extends Model
{
    use SoftDeletes, ModelCodeGenerator;
    protected $table = 'warehouse_stock_transfer_master';
    protected $primaryKey = 'stock_transfer_master_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'status',
        'created_by',
        'remarks',
        'source_warehouse_code',
        'destination_warehouse_code'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->stock_transfer_master_code = $model->generateWarehouseStockTransferCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateWarehouseStockTransferCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'WST', 1000, true);
    }

    public function warehouseStockTransferDetails()
    {
        return $this->hasOne(WarehouseStockTransferDetail::class, 'stock_transfer_master_code', 'stock_transfer_master_code');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function sourceWarehouses()
    {
        return $this->belongsTo(Warehouse::class, 'source_warehouse_code', 'warehouse_code');
    }

    public function destinationWarehouses()
    {
        return $this->belongsTo(Warehouse::class, 'destination_warehouse_code', 'warehouse_code');
    }

    public function warehouseTransferStocks()
    {
        return $this->hasMany(WarehouseTransferStock::class, 'stock_transfer_master_code', 'stock_transfer_master_code');
    }

    public function warehouseTransferLosses()
    {
        return $this->hasMany(WarehouseTransferLoss::class, 'stock_transfer_master_code', 'stock_transfer_master_code');
    }

    public function warehouseStockTransferMeta()
    {
        return $this->hasMany(WarehouseStockTransferMasterMeta::class, 'stock_transfer_master_code', 'stock_transfer_master_code');
    }
}