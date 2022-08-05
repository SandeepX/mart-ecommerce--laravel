<?php

namespace App\Modules\ManagerDiary\Resources\VisitClaimRedirection;

use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimScanRedirection;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitClaimRedirectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'title' => $this->title,
            'image' => photoToUrl($this->image,url(StoreVisitClaimScanRedirection::IMAGE_PATH)),
            'app_page' => $this->app_page,
            'external_link' => $this->external_link,
            'priority' => (isset($this->external_link) ? 'external_link' : 'app_page')
        ];
        return $result;
    }

}
