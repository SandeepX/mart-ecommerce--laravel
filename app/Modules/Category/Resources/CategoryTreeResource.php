<?php

namespace App\Modules\Category\Resources;

use App\Modules\Category\Models\CategoryMaster;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryTreeResource extends JsonResource
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
            'slug' => $this->slug,
            'upper_category_code' => $this->upper_category_code,
            'has_children' => $this->hasChildren(),
            'category_image' =>$this->getCategoryImage(),
            'category_icon' =>$this->getCategoryIcon(),
            // 'children'    => ($this->lowerCategories)->map(function ($category) {
            //     return [
            //         'id' => $category->id,
            //         'category_name' => $category->category_name,
            //         'category_code' => $category->category_code,
            //         'upper_category_code' => $category->upper_category_code,
            //         'has_children' => $category->hasChildren(),
            //         'children' => self::collection($category->lowerCategories)
            //     ];
            // })
            'children'    => self::collection($this->lowerCategories)

        ];
    }
}
