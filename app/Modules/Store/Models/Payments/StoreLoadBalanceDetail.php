<?php
/**
 * Created by VScode.
 * User: Sandeep
 * Date: 12/16/2020
 * Time: 4:25 PM
 */

namespace App\Modules\Store\Models\Payments;


use App\Modules\Application\Traits\ModelCodeGenerator;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreLoadBalanceDetail extends Model
{

    use ModelCodeGenerator;

    protected $table = 'store_load_balance_details';
    protected $primaryKey = 'store_load_balance_detail_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [

        'store_load_balance_detail_code',
        'store_balance_master_code',
        'store_misc_payment_code',

    ];



    const RECORDS_PER_PAGE=10;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->store_load_balance_detail_code  = $model->generateLoadBalanceDetailCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateLoadBalanceDetailCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'LBD', '1000', false);
    }

    public function storebalancemaster()
    {
        return $this->belongsTo(StoreBalanceMaster::class, 'store_balance_master_code ', 'store_balance_transaction');
    }

    public function storemiscellaneouspayments()
    {
        return $this->belongsTo(StoreBalanceMaster::class, 'store_misc_payment_code', 'store_misc_payment_code');
    }



}
