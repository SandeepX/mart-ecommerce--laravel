<?php

namespace App\Modules\Product\Resources;
use App\Modules\Product\Models\ProductMaster;
use Illuminate\Http\Resources\Json\ResourceCollection;


class MinimalProductCollection extends ResourceCollection
{
   

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  MinimalProductResource::collection($this->collection);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function with($request)
    {
        return [
            'error' => false,
            'code' => 200
        ];
    }

    
}

