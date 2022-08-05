<?php

namespace App\Modules\Store\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CashReceivedBalanceDetail extends Model
{
    use ModelCodeGenerator;

    protected $table = 'cash_received_balance_details';
    protected $primaryKey = 'store_crbd_code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'store_crbd_code',
        'store_balance_master_code',
        'ref_bill_no',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_crbd_code = $model->generateCode();
            $model->created_by = getAuthUserCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
            $model->updated_by = getAuthUserCode();
        });
        static::deleting(function ($model) {
            $model->deleted_at = Carbon::now();
            $model->deleted_by = getAuthUserCode();
        });

    }

    public function generateCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SCRBD', '1000', false);
    }

    public function storeBalanceMaster(){
        return $this->belongsTo(StoreBalanceMaster::class,'store_balance_master_code');
    }


}
