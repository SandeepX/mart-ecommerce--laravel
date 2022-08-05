<?php

namespace App\Modules\Store\Models\Payments;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class MiscellaneousPaymentRemark extends Model
{
    use ModelCodeGenerator;
    protected $table = 'miscellaneous_payment_remarks';
    protected $primaryKey = 'miscellaneous_payment_remark_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'miscellaneous_payment_remark_code',
        'store_misc_payment_code',
        'remark',
        'created_by'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->miscellaneous_payment_remark_code = $model->generateMiscellaneousPaymentRemarkCode();
        });
    }

    public function generateMiscellaneousPaymentRemarkCode(){
        return $this->generateModelCodeWithOutZeroPadding($this, $this->primaryKey, 'MPRC', '1000', false);
    }

}
