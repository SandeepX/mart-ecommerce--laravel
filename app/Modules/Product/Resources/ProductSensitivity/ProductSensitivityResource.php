<?php

namespace App\Modules\Product\Resources\ProductSensitivity;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductSensitivityResource extends JsonResource
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
            'sensitivity_code' => $this->sensitivity_code,
            'sensitivity_name' => $this->sensitivity_name,
            'remarks' => $this->remarks
        ];
    }
}
