<?php

namespace App\Modules\Store\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreOrderRemarkResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'remark' => $this->remark,
            'created_at'=> getReadableDate(getNepTimeZoneDateTime($this->created_at)),
        ];
    }

}
