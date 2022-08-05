<?php

namespace App\Modules\Types\Resources\UserType;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTypeApiResourceTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'id'=> $this->id,
            'user_type_code'=> $this->user_type_code,
            'user_type_name'=> $this->user_type_name,
        ];
    }
}
