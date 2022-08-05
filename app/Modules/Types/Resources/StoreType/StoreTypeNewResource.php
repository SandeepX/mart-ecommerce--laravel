<?php

namespace App\Modules\Types\Resources\StoreType;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class StoreTypeNewResource extends JsonResource
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
            'store_type_code'=>$this->store_type_code,
            'store_type_name'=>$this->store_type_name,
            'store_type_slug'=>$this->store_type_slug,
            'description'=>$this->description,
            'short_description'=>Str::limit($this->description,300),
            'is_active'=>$this->is_active,
            'image' => photoToUrl($this->image, asset('uploads/storetypes/images/'))
        ];
    }
}
