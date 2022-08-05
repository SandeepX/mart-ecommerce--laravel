<?php

namespace App\Modules\ManagerDiary\Resources\VisitClaim;

use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimRequestByManager;
use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimScanRedirection;
use App\Modules\ManagerDiary\Repositories\StoreVisitClaimScanRedirectionRepository;
use App\Modules\ManagerDiary\Resources\VisitClaimRedirection\VisitClaimRedirectionCollection;
use App\Modules\ManagerDiary\Resources\VisitClaimRedirection\VisitClaimRedirectionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreVisitClaimScanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $visitClaimRedirections =  (new StoreVisitClaimScanRedirectionRepository())->getActiveStoreVisitClaimRedirection();
        $result = [
            'store_visit_claim_request_code' => $this->store_visit_claim_request_code,
            'qr_info' => [
                'generated_at' => $this->created_at,
                'scanned_at'=>$this->qr_scanned_at
            ],
            'adds' =>  VisitClaimRedirectionResource::collection($visitClaimRedirections)
        ];
        return $result;
    }

}
