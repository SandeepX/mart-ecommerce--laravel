<?php

namespace App\Modules\ManagerDiary\Resources\Diary;

use App\Modules\Location\Repositories\LocationHierarchyRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use function getReadableDate;

class ManagerDiaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $managerDiaryWardLocation = (new LocationHierarchyRepository)->getLocationByCode($this->ward_code);
        $managerDiaryLocTree = (new LocationHierarchyRepository)->getLocationPath($managerDiaryWardLocation);

        $result = [
            'manager_diary_code' => $this->manager_diary_code,
            'manager_code' => $this->manager_code,
            'store_name' => $this->store_name,
            'referred_store_code' => $this->referred_store_code,
            'referred_store_name' => isset($this->referred_store_code) ? $this->referredStore->store_name : NULL,
            'owner_name' => $this->owner_name,
            'phone_no' => $this->phone_no,
            'alt_phone_no' => $this->alt_phone_no,
            'pan_no' => $this->pan_no,
            'ward_code' => $this->ward_code,
            'full_location' => $this->full_location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'business_investment_amount' => $this->business_investment_amount,
            'created_at' => getReadableDate(getNepTimeZoneDateTime($this->created_at)),
            'updated_at' => getReadableDate(getNepTimeZoneDateTime($this->updated_at)),
            'location_details' => [
                    'province' => $managerDiaryLocTree['province'],
                    'district' => $managerDiaryLocTree['district'],
                    'municipality' => $managerDiaryLocTree['municipality'],
                    "ward" => $managerDiaryLocTree['ward'],
             ]
        ];

        return $result;
    }
}
