<?php

namespace App\Modules\Product\Resources;

use App\Modules\Product\Models\PVGroupBulkImage;
use Illuminate\Http\Resources\Json\JsonResource;

class PVGroupBulkImageResource extends JsonResource
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
            'pv_group_bulk_image_code' => $this->pv_group_bulk_image_code,
            'product_variant_group_code' => $this->product_variant_group_code,
            'image' => photoToUrl($this->image,url(PVGroupBulkImage::IMAGE_PATH))
        ];
    }
}
