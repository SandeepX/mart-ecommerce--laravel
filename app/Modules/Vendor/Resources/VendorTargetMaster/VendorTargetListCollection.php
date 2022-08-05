<?php


/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 3/6/2021
 * Time: 2:53 PM
 */

namespace App\Modules\Vendor\Resources\VendorTargetMaster;


use Illuminate\Http\Resources\Json\ResourceCollection;

class VendorTargetListCollection extends ResourceCollection
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
            'data' => VendorTargetMasterResource::collection($this->collection),
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
