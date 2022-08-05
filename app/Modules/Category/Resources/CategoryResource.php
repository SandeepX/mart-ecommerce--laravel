<?php

namespace App\Modules\Category\Resources;

use App\Modules\Category\Models\CategoryMaster;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'slug' => $this->slug,
            'category_image' => $this->getCategoryImage(),
            'category_icon' => $this->getCategoryIcon(),
            'has_children' => $this->hasChildren(),
             'children'    => ($this->lowerCategories) ?
             ($this->lowerCategories)->map(function ($category) {
                return [
                    'id' => $category->id,
                    'category_name' => $category->category_name,
                    'category_code' => $category->category_code,
                    'upper_category_code' => $category->upper_category_code,
                    'has_children' => $category->hasChildren(),
                    'category_image' =>$category->getCategoryImage(),
                    'category_icon' =>$category->getCategoryIcon(),
                ];
            }) : []
        ];
    }
}
