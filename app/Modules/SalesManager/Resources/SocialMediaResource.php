<?php


namespace App\Modules\SalesManager\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SocialMediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'sm_code' => $this->sm_code,
            'social_media_name' => $this->social_media_name,

        ];
    }
}

