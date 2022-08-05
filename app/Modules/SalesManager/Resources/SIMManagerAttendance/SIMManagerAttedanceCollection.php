<?php

/**
 * Created by PhpStorm.
 * User: Sandeep pant
 * Date: 11/28/2021
 * Time: 12:18 PM
 */

namespace App\Modules\SalesManager\Resources\SIMManagerAttendance;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SIMManagerAttedanceCollection extends ResourceCollection
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
            'data' => SIMManagerAttendanceResource::collection($this->collection),
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

