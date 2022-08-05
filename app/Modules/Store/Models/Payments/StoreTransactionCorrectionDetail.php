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

class StoreTransactionCorrectionDetail extends Model
{
    use ModelCodeGenerator;

    protected $table = 'store_transaction_correction_detail';
    protected $primaryKey = 'store_transaction_correction_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_balance_master_code',
        'transaction_code',
        'created_by',
        'updated_by'
    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_transaction_correction_code = $model->generateStoreTransactionCorrectionCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateStoreTransactionCorrectionCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'STCD', '1000', false);
    }

    public function storeBalanceMaster(){
        return $this->belongsTo(StoreBalanceMaster::class,'store_balance_master_code','store_balance_master_code');
    }

}
