<?php

namespace App\Modules\Category\Resources;

use App\Modules\Category\Models\CategoryMaster;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryPathResource extends JsonResource
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
            'category_code' => $this->category_code,
            'category_path' => $this->path
        ];
    }
}
