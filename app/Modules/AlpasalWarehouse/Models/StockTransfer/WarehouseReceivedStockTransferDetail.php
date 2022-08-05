<?php

namespace App\Modules\AlpasalWarehouse\Models\StockTransfer;

use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Package\Models\PackageType;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseReceivedStockTransferDetail extends Model
{
    use SoftDeletes, ModelCodeGenerator;
    protected $table = 'warehouse_received_stock_transfer_details';
    protected $primaryKey = 'received_stock_transfer_details_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'stock_transfer_master_code',
        'warehouse_product_master_code',
        'received_quantity',
        'package_quantity',
        'package_code',
        'product_packaging_history_code',
        'created_by',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->received_stock_transfer_details_code = $model->generateWarehouseReceivedStockTransferDetailCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateWarehouseReceivedStockTransferDetailCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'WRSTD', 1000, true);
    }

    public function warehouseStockTransfer()
    {
        return $this->belongsTo(WarehouseStockTransfer::class, 'stock_transfer_master_code', 'stock_transfer_master_code');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_code');
    }

    public function warehouseProducts()
    {
        return $this->belongsToMany(WarehouseProductMaster::class, 'warehouse_product_master_code', 'warehouse_product_master_code');
    }

    public function packageType()
    {
        return $this->belongsTo(PackageType::class, 'package_code', 'package_code');
    }
}
