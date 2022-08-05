<?php

namespace App\Modules\ManagerDiary\Resources\VisitClaimRedirection;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VisitClaimRedirectionCollection extends ResourceCollection
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
            'data' => VisitClaimRedirectionResource::collection($this->collection),
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
