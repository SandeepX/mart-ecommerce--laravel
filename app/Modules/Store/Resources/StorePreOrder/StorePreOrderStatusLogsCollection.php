<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/29/2020
 * Time: 3:24 PM
 */

namespace App\Modules\Store\Resources\StorePreOrder;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StorePreOrderStatusLogsCollection extends ResourceCollection
{

    public function toArray($request)
    {

        return  StorePreOrderStatusLogResource::collection($this->collection);
    }

    public function with($request)
    {
        return [
            'error' => false,
            'code' => 200
        ];
    }
}
