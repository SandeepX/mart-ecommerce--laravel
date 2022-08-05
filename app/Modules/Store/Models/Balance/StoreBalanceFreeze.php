<?php
/**
 * Created by VScode.
 * User: Sandeep
 * Date: 12/17/2020
 * Time: 11:25 PM
 */

namespace App\Modules\Store\Models\Balance;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Store;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreBalanceFreeze extends Model
{

    use ModelCodeGenerator;

    protected $table = 'store_balance_freezes';
    protected $primaryKey = 'store_balance_freeze_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'store_balance_freeze_code',
        'store_code',
        'amount',
        'status',
        'source',
        'source_code',

    ];


    const RECORDS_PER_PAGE=10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_balance_freeze_code = $model->generateStoreBalanceWithdrawRequestCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateStoreBalanceWithdrawRequestCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SBF', '1000', false);
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_code', 'store_code');
    }

}
