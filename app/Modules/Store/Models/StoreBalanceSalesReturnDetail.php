<?php

namespace App\Modules\Store\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreBalanceSalesReturnDetail extends Model
{
    use ModelCodeGenerator;

    protected $table = 'store_balance_sales_return_details';
    protected $primaryKey = 'store_balance_sales_return_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [

        'store_balance_sales_return_detail_code',
        'store_balance_master_code',
        'store_order_code'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_balance_sales_return_detail_code = $model->generateCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SBSRD', '1000', false);
    }

    public function storeBalanceMaster()
    {
        return $this->belongsTo(StoreBalanceMaster::class, 'store_balance_master_code');
    }

    public function storeOrder()
    {
        return $this->belongsTo(StoreOrder::class, 'store_order_code');
    }
}



