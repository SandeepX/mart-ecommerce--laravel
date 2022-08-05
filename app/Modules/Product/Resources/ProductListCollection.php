<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/25/2020
 * Time: 1:53 PM
 */

namespace App\Modules\Product\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductListCollection extends ResourceCollection
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
            'data' => ProductListResource::collection($this->collection),
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