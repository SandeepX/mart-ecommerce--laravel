<?php


namespace App\Modules\Brand\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;


class BrandDetailsCollection extends ResourceCollection
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
                'brand'=>BrandResource::collection($this->collection),
                'sliders'=>BrandSliderResource::collection($this->collection)
             //   'brand_followers'=>BrandFollowerNumberResource::collection($this->brand_followers_count),
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



