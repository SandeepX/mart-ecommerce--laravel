<?php

namespace App\Modules\ManagerDiary\Resources\VisitClaim;

use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimRequestByManager;
use Illuminate\Http\Resources\Json\JsonResource;
use function getReadableDate;

class StoreVisitClaimRequestResource  extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $statusText = [
            'drafted' => [
                'text' => 'drafted_at',
                'timestamp' => $this->created_at
            ],
            'pending' => [
                'text' => 'submitted_at',
                'timestamp' => $this->qr_scanned_at
            ],
            'rejected' => [
                'text' => 'rejected_at',
                'timestamp' => $this->responded_at
            ],
            'verified' => [
                'text' => 'verified_at',
                'timestamp' => $this->responded_at
            ]
        ];
        $status = $this->status;
        $statusTextAt = $statusText[$status]['text'];
        $statusTimeStamp = $statusText[$status]['timestamp'];
        $result = [
            'store_visit_claim_request_code' => $this->store_visit_claim_request_code,
            'manager_diary' => [
                'store_name' => $this->managerDiary->store_name,
                'full_location' => $this->managerDiary->full_location,
                'pan_no'=>$this->managerDiary->pan_no,
                'phone_no' => $this->managerDiary->phone_no,
                'referred_store_code' => (isset($this->managerDiary) && isset($this->managerDiary->referredStore)) ? $this->managerDiary->referredStore->store_code : NULL,
                'referred_store_name' => (isset($this->managerDiary) && isset($this->managerDiary->referredStore)) ?  $this->managerDiary->referredStore->store_name : NULL,
            ],
            'status_info' => [
                'claim_status' => $status,
                $statusTextAt =>$statusTimeStamp
            ],
            'qr_info' => [
                'generated_at' => $this->created_at,
                'scanned_at'=>$this->qr_scanned_at
            ],
            'submit_info' => [
                'visit_image' => isset($this->visit_image) ? photoToUrl($this->visit_image,url(StoreVisitClaimRequestByManager::VISIT_IMAGE_PATH)) : NULL,
                'submitted_at' => $this->submitted_at
            ],
            'updated_at' => getReadableDate(getNepTimeZoneDateTime($this->updated_at))
        ];
        return $result;
    }

}
