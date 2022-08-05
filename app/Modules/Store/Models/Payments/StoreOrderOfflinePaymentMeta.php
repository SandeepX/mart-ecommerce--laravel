<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/1/2020
 * Time: 12:43 PM
 */

namespace App\Modules\Store\Models\Payments;


use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StoreOrderOfflinePaymentMeta extends Model
{

    use ModelCodeGenerator;

    protected $table = 'store_order_offline_payment_meta';
    protected $primaryKey = 'payment_meta_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'payment_meta_code','store_order_offline_payment_code','key','value'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->payment_meta_code = $model->generateStoreOrderPaymentMetaCode();
        });

        static::updating(function ($model) {
            $model->updated_at = Carbon::now();
        });

    }

    public function generateStoreOrderPaymentMetaCode()
    {
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'SOPM', '1000', false);
    }

    public function storeOrderPayment()
    {
        return $this->belongsTo(StoreOrderOfflinePayment::class, 'store_order_offline_payment_code', 'store_offline_payment_code');
    }
}