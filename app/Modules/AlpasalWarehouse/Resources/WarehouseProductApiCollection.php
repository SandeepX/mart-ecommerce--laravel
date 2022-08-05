<?php

namespace App\Modules\AlpasalWarehouse\Resources;
use Illuminate\Http\Resources\Json\ResourceCollection;


class WarehouseProductApiCollection extends ResourceCollection
{


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return  WarehouseProductResource::collection($this->collection);
        return [
            'data' => WarehouseProductResource::collection($this->collection),
            'links' => [
                'self' => 'link-value',
            ],
        ];
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


