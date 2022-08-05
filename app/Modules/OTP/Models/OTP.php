<?php


namespace App\Modules\OTP\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    protected $table = 'otp_verifications';

    protected $fillable = [
        'entity',
        'entity_code',
        'otp_code',
        'useable',
        'otp_request_via',
        'expires_at',
        'purpose',
        'purpose_verified'
    ];

    const PURPOSE  = ['account_registration'];
    const OTP_REQUEST_VIA = ['phone','email'];
    const RECORDS_PER_PAGE = 10;


}

