<?php


namespace App\Modules\SalesManager\Resources;

use App\Modules\SalesManager\Transformers\ManagerDocumentTransformer;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserDoc;
use App\Modules\User\Resources\UserDocForManagerResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Location\Repositories\LocationHierarchyRepository;

class ManagerDetailResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $managerTemporaryLocation = (new LocationHierarchyRepository)->getLocationByCode($this->temporary_ward_code);
        $managerTempLocTree = (new LocationHierarchyRepository)->getLocationPath($managerTemporaryLocation);

        $managerPermanentLocation = (new LocationHierarchyRepository)->getLocationByCode($this->permanent_ward_code);
        $managerPermanentLocTree = (new LocationHierarchyRepository)->getLocationPath($managerPermanentLocation);

        $data = [
            'manager_name' => $this->manager_name,
            'manager_code' => $this->manager_code,
            'referral_code' => $this->referral_code,
            'avatar' => photoToUrl($this->user->avatar,url(User::AVATAR_UPLOAD_PATH)),
            //'store_logo' => photoToUrl($this->store_logo,asset('uploads/stores/logos')),
            'contact_details' => [
                'contact_mobile' => $this->manager_phone_no,
                'contact_phone' => $this->manager_phone_no,
                'contact_email'=> $this->manager_email,
            ],
            'location_details' => [
                'temporary'=>[
                    'province' => $managerTempLocTree['province'],
                    'district' => $managerTempLocTree['district'],
                    'municipality' => $managerTempLocTree['municipality'],
                    "ward" => $managerTempLocTree['ward'],
                ],
                'permanent'=>[
                    'province' => $managerPermanentLocTree['province'],
                    'district' => $managerPermanentLocTree['district'],
                    'municipality' => $managerPermanentLocTree['municipality'],
                    "ward" => $managerPermanentLocTree['ward'],
                ],

            ],
        ];

        $data['document'] =  (new ManagerDocumentTransformer($this->managerDocs))->transform();

        return $data;
    }




}
