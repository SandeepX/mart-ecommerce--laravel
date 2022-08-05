<?php

namespace App\Modules\PromotionLinks\Resources;

use App\Modules\PromotionLinks\Models\PromotionLink;
use Illuminate\Http\Resources\Json\JsonResource;

class SinglePromotionLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $promotionLinks = [
            'filename' => $this->file,
            'file' => photoToUrl($this->file, url(PromotionLink::PROMOTION_FILE_PATH)),
            'link_code' => $this->link_code,
            'title' => $this->title,
            'description' => $this->description,
            'image' => photoToUrl($this->image, url(PromotionLink::IMAGE_PATH)),
            'meta' => [
                'property'=>[
                    'og:title' => $this->og_title,
                    'og:description' => $this->og_description,
                    'og:image' => photoToUrl($this->og_image, url(PromotionLink::OG_IMAGE_PATH))
                 ],
                'name' => [
                    'title' => $this->og_title,
                    'description' => $this->og_description,
                    'image' => photoToUrl($this->og_image, url(PromotionLink::OG_IMAGE_PATH))
                ]
             ]
        ];
        return $promotionLinks;
    }

}
