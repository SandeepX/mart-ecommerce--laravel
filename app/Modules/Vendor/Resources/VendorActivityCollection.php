<?php


namespace App\Modules\Vendor\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class VendorActivityCollection extends ResourceCollection
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
            'data'=>VendorActivityResource::collection($this->collection),
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
