<?php

namespace App\Modules\Types\Resources\CategoryType;

use Illuminate\Http\Resources\Json\JsonResource;

class MinimalCategoryTypeResource extends JsonResource
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
            'category_type_code' => $this->category_type_code,
            'category_type_name' => $this->category_type_name,
        ];
    }
}
