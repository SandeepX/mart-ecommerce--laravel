<?php


namespace App\Modules\AlpasalWarehouse\Models\StockTransfer;


use App\Modules\AlpasalWarehouse\Models\WarehouseProductMaster;
use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Package\Models\PackageType;
use App\Modules\Product\Models\ProductPackagingHistory;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseStockTransferDetail extends Model
{
    use SoftDeletes, ModelCodeGenerator;
    protected $table = 'warehouse_stock_transfer_details';
    protected $primaryKey = 'stock_transfer_details_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'stock_transfer_master_code',
        'warehouse_product_master_code',
        'created_by',
        'sending_quantity',
        'package_quantity',
        'package_code',
        'product_packaging_history_code'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->stock_transfer_details_code = $model->generateWarehouseStockTransferDetailCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });
    }

    public function generateWarehouseStockTransferDetailCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'WSTD', 1000, true);
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

    public function warehouseTransferLosses()
    {
        return $this->hasMany(WarehouseTransferLoss::class, 'stock_transfer_details_code', 'stock_transfer_details_code');
    }

    public function packageType()
    {
        return $this->belongsTo(PackageType::class, 'package_code', 'package_code');
    }
    public function productPackagingHistory()
    {
        return $this->belongsTo(ProductPackagingHistory::class, 'product_packaging_history_code', 'product_packaging_history_code');
    }
}
