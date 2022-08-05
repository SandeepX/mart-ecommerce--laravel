<?php

namespace App\Modules\EnquiryMessage\Resources;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Modules\EnquiryMessage\Models\EnquiryMessage;
use App\Modules\EnquiryMessage\Resources\EnquiryMessageResource;



class EnquiryMessageCollection extends ResourceCollection
{


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  EnquiryMessageResource::collection($this->collection);
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


