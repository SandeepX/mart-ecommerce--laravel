<?php

namespace App\Modules\Types\Resources\CompanyType;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyTypeApiResource extends JsonResource
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
            'id' => $this->id,
            'company_type_code' => $this->company_type_code,
            'company_type_name' => $this->company_type_name,
        ];
    }
}
