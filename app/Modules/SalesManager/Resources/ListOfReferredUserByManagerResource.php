<?php

namespace App\Modules\SalesManager\Resources;

use App\Modules\Location\Repositories\LocationHierarchyRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ListOfReferredUserByManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $userLocation = (new LocationHierarchyRepository())->getLocationByCode($this->location_code);
        $userLocTree = (new LocationHierarchyRepository)->getLocationPath($userLocation);
        $result = [
            'user_entity_code' => $this->user_entity_code,
            'user_name' => $this->user_name,
            'user_type' => $this->user_type,
            'location_details' => [
                                'province' => $userLocTree['province'],
                                'district' => $userLocTree['district'],
                                'municipality' => $userLocTree['municipality'],
                                "ward" => $userLocTree['ward'],
                            ],
            'phone_number' => $this->phone_number,
            'created_at' => getReadableDate(getNepTimeZoneDateTime($this->created_at))
        ];

        return $result;
    }

}
