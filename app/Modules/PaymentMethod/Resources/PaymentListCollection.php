<?php

namespace App\Modules\PaymentMethod\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentListCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this->links());
        return [
            'data' => PaymentListResource::collection($this->collection),
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
