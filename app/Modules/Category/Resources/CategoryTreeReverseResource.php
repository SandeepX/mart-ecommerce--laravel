<?php

namespace App\Modules\Category\Resources;

use App\Modules\Category\Models\CategoryMaster;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryTreeReverseResource extends JsonResource
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
            'category_name' => $this->category_name,
            'category_code' => $this->category_code,
            'upper_category_code' => $this->upper_category_code,
            'has_children' => $this->hasChildren(),
            'parent'    => new self($this->upperCategory),
            'category_image' =>$this->getCategoryImage(),
            'category_icon' =>$this->getCategoryIcon(),

        ];
    }
}
