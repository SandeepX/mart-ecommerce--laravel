<?php

namespace App\Modules\User\Resources;

use App\Modules\User\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MinimalUserResource extends JsonResource
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
            //'id' => $this->id,
            'user_code' => $this->user_code,
            'avatar' => photoToUrl($this->avatar,asset(User::AVATAR_UPLOAD_PATH)),
            'user_type' => $this->userType->slug,
            'name' => $this->name,
//            'referral_code' => $this->manager->referral_code,
//            'manager_code' =>$this->manager->manager_code,
            'login_email' => $this->login_email,
            'login_phone' =>$this->login_phone,
            'is_first_login' => $this->is_first_login,
            'is_email_verified' => ($this->email_verified_at == null ? 0 : 1),
            'is_phone_verified' => ($this->phone_verified_at == null ? 0 : 1)
        ];
    }
}
