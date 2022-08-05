<?php

namespace App\Modules\Vendor\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title'=>$this->subject,
            'created_at'=>$this->created_at->diffForHumans()
        ];
    }
}
