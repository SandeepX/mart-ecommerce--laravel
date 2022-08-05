<?php

namespace App\Modules\Wallet\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WalletTransactionRemark extends Model
{
    use ModelCodeGenerator;
    protected $table = 'wallet_transaction_remarks';
    protected $primaryKey = 'wallet_transaction_remark_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'wallet_transaction_remark_code',
        'wallet_transaction_code',
        'remark',
        'created_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->wallet_transaction_remark_code = $model->generateWalletTransactionRemarkCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateWalletTransactionRemarkCode(){
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'WTRC', '1000', false);
    }

}
