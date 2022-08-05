<?php


namespace App\Modules\SalesManager\Resources\SMIManagerResource;

use App\Modules\SalesManager\Models\ManagerSMISetting;
use App\Modules\SalesManager\Models\SocialMedia;
use App\Modules\SalesManager\Resources\ManagerSMILinks\ManagerSMILinksCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class SMIManagerDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $salary = ManagerSMISetting::select('salary')->latest()->first();
        $appliedLinks = $this->managerLinks->map(function ($appliedLink){
            return [
                'sm_code' => $appliedLink->sm_code,
                'social_media_name' => $appliedLink->socialMedia->social_media_name,
                'links' =>    json_decode($appliedLink->social_media_links)
            ];
        });
        $appliedSMCodes = $this->managerLinks->pluck('sm_code')->toArray();
        $notAppliedSocialMedias = SocialMedia::whereNotIn('sm_code',$appliedSMCodes)->get();
        $notAppliedSocialMedias = $notAppliedSocialMedias->map(function ($socialMedia){
            return [
                'sm_code' => $socialMedia->sm_code,
                'social_media_name' => $socialMedia->social_media_name,
                'links' =>   []
            ];
        });
        $mergedSMLinks = $appliedLinks->merge($notAppliedSocialMedias);


        return [
            'manager_code' => $this->manager_code,
            'salary' => $salary['salary'],
            'status' => ucfirst($this->status),
            'is_active' => $this->is_active,
            'allow_edit' => $this->allow_edit,
            'remarks' => ucfirst($this->remarks),
            'allow_edit_remarks' => $this->allow_edit_remarks,
            'created_at' => date_format($this->created_at,"d-M-Y"),
            'social_media_links' => $mergedSMLinks,
            'can_update' => $this->canManagerUpdateSMIDetail()
        ];
    }
}



