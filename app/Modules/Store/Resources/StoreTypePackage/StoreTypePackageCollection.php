<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/20/2020
 * Time: 3:18 PM
 */
namespace App\Modules\Store\Resources\StoreTypePackage;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StoreTypePackageCollection extends ResourceCollection
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
            'data' => StoreTypePackagesResource::collection($this->collection),
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
