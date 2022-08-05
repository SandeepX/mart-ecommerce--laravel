<?php


/**
 * Created by PhpStorm.
 * User: Sandeep
 * Date: 3/12/2021
 * Time: 2:53 PM
 */

namespace App\Modules\SalesManager\Resources\VendorTargetIncentative;


use Illuminate\Http\Resources\Json\ResourceCollection;

class VendorTargetIncentativeCollectionForSalesmanager extends ResourceCollection
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
            'data' => VendorTargetIncentativeForManagerResource::collection($this->collection),
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


