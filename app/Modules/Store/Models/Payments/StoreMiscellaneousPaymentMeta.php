<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/22/2020
 * Time: 12:41 PM
 */

namespace App\Modules\Store\Models\Payments;


use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreMiscellaneousPaymentMeta extends Model
{

    use ModelCodeGenerator;

    protected $table = 'store_miscellaneous_payments_meta';
    protected $primaryKey = 'payment_meta_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'payment_meta_code','store_misc_payment_code','key','value'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->payment_meta_code = $model->generateMiscPaymentMetaCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateMiscPaymentMetaCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SMPM', '1000', false);
    }

    public function storePayment()
    {
        return $this->belongsTo(StoreMiscellaneousPayment::class, 'store_misc_payment_code', 'store_misc_payment_code');
    }

}
