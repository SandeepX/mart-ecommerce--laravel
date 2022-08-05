<?php

namespace App\Modules\User\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_type' => $this->userType->slug,
            'name' => $this->name,
            'login_email' => $this->login_email,
            'login_phone' =>$this->login_phone,
            'avatar' => $this->avatar ? photoToUrl($this->avatar,asset('uploads/user/avatar/')) : null,
            'is_email_verified' => $this->is_email_verified_at,
            'email_verified_at' => $this->email_verified_at,
            'phone_verified_at' => $this->phone_verified_at,
            'is_first_login' => $this->is_first_login,
            'last_login_at' => $this->last_login_at,
            'last_login_ip' => $this->last_login_ip
        ];
    }
}
