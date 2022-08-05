<?php


namespace App\Modules\SalesManager\Resources\ManagerSMILinks;


use Illuminate\Http\Resources\Json\JsonResource;

class ManagerSMILinksResource extends JsonResource
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
            'sm_code' => $this->sm_code,
            'social_media_name' => $this->socialMedia->social_media_name,
            'links' => json_decode($this->social_media_links)
        ];
    }
}




