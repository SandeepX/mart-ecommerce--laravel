<?php

namespace App\Modules\SalesManager\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ListOfReferredManagersByManagerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => ListOfReferredManagerByManagerResource::collection($this->collection),
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }

    public function with($request)
    {
        return [
            'error' => false,
            'code' => 200
        ];
    }

}
