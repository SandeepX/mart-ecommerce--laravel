<?php


namespace App\Modules\Store\Resources\StorePreOrder;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StorePreOrderListingCollection extends ResourceCollection
{

    public function toArray($request)
    {

        return  StorePreOrderListingResource::collection($this->collection);
    }

    public function with($request)
    {
        return [
            'error' => false,
            'code' => 200
        ];
    }
}
