<?php

namespace App\Modules\Brand\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'brand_name' => $this->brand_name,
            'brand_code' => $this->brand_code,
            'brand_slug'=>$this->slug,
            'brand_logo' => url('/uploads/brand/'.$this->brand_logo),
            'products_count'=>$this->products_count??count($this->products()->where('is_Active',1)->qualifiedToDisplay()->get()),
            'remarks' => $this->remarks
        ];
    }
}
