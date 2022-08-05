<?php
/**
 * Created by VScode.
 * User: Sandeep
 * Date: 12/16/2020
 * Time: 12:25 PM
 */

namespace App\Modules\Store\Models\Payments;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SaleReconciliation extends Model
{
    use ModelCodeGenerator;

    protected $table = 'sales_reconciliation';
    protected $primaryKey = 'sales_reconciliation_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_balance_master_code',
        'order_code',
        'ref_bill_no',
        'type',
        'created_by',
        'updated_by'
    ];

    const TYPE = ['normal_store_order','store_pre_order'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->sales_reconciliation_code = $model->generateSalesReconciliationcoCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateSalesReconciliationcoCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SR', '1000', false);
    }

    public function storeBalanceMaster(){
        return $this->belongsTo(StoreBalanceMaster::class,'store_balance_master_code','store_balance_master_code');
    }

}
