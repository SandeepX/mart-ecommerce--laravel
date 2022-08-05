<?php


namespace App\Modules\Product\Resources\PreOrder;


use App\Modules\Product\Resources\AllWarehouseProductsListingResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AllWarehousePreOrderProductsListingCollection extends ResourceCollection
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  AllWarehousePreOrderProductsListingResource::collection($this->collection);
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
