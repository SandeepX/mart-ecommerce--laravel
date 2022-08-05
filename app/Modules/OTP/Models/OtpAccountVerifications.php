<?php

namespace App\Modules\OTP\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OtpAccountVerifications extends Model
{
    protected $table = 'otp_account_verifications';
    protected $fillable = [

        'otp_code',
        'otp_request_source',
        'otp_source_value',
        'expires_at',
        'useable'
    ];
    const OTP_REQUEST_SOURCE = ['phone','email'];
    const OTP_EXPIRY_PERIOD = '5 mins';
    const OTP_RESEND_PERIOD_IN_SECONDS = '60';
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->otp_code=random_int(1000, 9999);
            $model->expires_at = Carbon::now()->addMinutes(5);
        });
    }
}
