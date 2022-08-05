<?php


namespace App\Modules\B2cCustomer\Resources;

use App\Modules\B2cCustomer\Transformers\B2CUserDocumentTransformer;
use App\Modules\User\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Location\Repositories\LocationHierarchyRepository;

class B2CUserDetailResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->temporary_ward != null){
            $userTemporaryLocation = (new LocationHierarchyRepository)->getLocationByCode($this->temporary_ward);
            $userTempLocTree = (new LocationHierarchyRepository)->getLocationPath($userTemporaryLocation);
        }

        if($this->ward_code != null){
            $userPermanentLocation = (new LocationHierarchyRepository)->getLocationByCode($this->ward_code);
            $userPermanentLocTree = (new LocationHierarchyRepository)->getLocationPath($userPermanentLocation);
        }


        $data = [
            'user_name' => ucfirst($this->name),
            'user_code' => $this->user_code,
            'gender' => $this->gender,
            'referral_code' => $this->referral_code,
            'profile_image' => photoToUrl($this->avatar, asset(User::AVATAR_UPLOAD_PATH)),

            'contact_details' => [
                'contact_mobile' => $this->login_phone,
                'contact_phone' => $this->login_phone,
                'contact_email' => $this->login_email,
            ],
            'location_details' => [
                'temporary' => [
                    'province' => (isset($userTempLocTree)) ? $userTempLocTree['province']: null,
                    'district' => (isset($userTempLocTree)) ? $userTempLocTree['district']: null,
                    'municipality' => (isset($userTempLocTree)) ? $userTempLocTree['municipality']: null,
                    "ward" =>  (isset($userTempLocTree)) ? $userTempLocTree['ward']: null,
                ],
                'permanent' => [
                    'province' => (isset($userPermanentLocTree)) ? $userPermanentLocTree['province']:null,
                    'district' => (isset($userPermanentLocTree)) ? $userPermanentLocTree['district'] :null,
                    'municipality' => (isset($userPermanentLocTree)) ? $userPermanentLocTree['municipality'] :null,
                    "ward" => (isset($userPermanentLocTree)) ?  $userPermanentLocTree['ward'] :null,
                ],

            ],
        ];
       if(count($this->userDocs) > 0){
           $data['document'] = (new B2CUserDocumentTransformer($this->userDocs))->transform();
       }else{
           $data['document'] = [];
       }
        return $data;
    }


}

