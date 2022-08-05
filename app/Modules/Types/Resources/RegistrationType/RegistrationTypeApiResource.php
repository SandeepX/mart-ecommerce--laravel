<?php

namespace App\Modules\Types\Resources\RegistrationType;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationTypeApiResource extends JsonResource
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
            'registration_type_code'=> $this->registration_type_code,
            'registration_type_name'=> $this->registration_type_name,
        ];
    }
}
