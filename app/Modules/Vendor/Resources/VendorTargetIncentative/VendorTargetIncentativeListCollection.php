<?php

/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 3/8/2021
 * Time: 2:53 PM
 */

namespace App\Modules\Vendor\Resources\VendorTargetIncentative;


use Illuminate\Http\Resources\Json\ResourceCollection;

class VendorTargetIncentativeListCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => VendorTargetIncentativeResource::collection($this->collection),
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

