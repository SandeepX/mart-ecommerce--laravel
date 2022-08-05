<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/20/2020
 * Time: 3:20 PM
 */

namespace App\Modules\Store\Resources\StorePayment;


use Illuminate\Http\Resources\Json\ResourceCollection;

class StoreMiscellaneousPaymentMinimalCollection extends ResourceCollection
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
            'data' => StoreMiscellaneousPaymentMinimalResource::collection($this->collection),
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