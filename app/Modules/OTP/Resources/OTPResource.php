<?php

namespace App\Modules\OTP\Resources;

use App\Modules\OTP\Models\OtpAccountVerifications;
use Illuminate\Http\Resources\Json\JsonResource;

class OTPResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'expires_at' => $this->expires_at,
            'expiry_period'=> OtpAccountVerifications::OTP_EXPIRY_PERIOD,
            'created_at'=> $this->created_at,
            'resend_period'=> OtpAccountVerifications::OTP_RESEND_PERIOD_IN_SECONDS
        ];
    }
}
