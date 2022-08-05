<?php
/**
 * Created by PhpStorm.
 * User: Sandeep pant
 * Date: 11/26/2021
 * Time: 5:18 PM
 */

namespace App\Modules\SalesManager\Resources\ManagerSMILinks;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ManagerSMILinksCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this->links());
        return [
            'data' => ManagerSMILinksResource::collection($this->collection),
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

