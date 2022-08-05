<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/20/2020
 * Time: 3:18 PM
 */

namespace App\Modules\LuckyDraw\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class StoreLuckydrawCollection extends ResourceCollection
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
            'data' => StoreLuckydrawResource::collection($this->collection),
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
