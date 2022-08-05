<?php


namespace App\Modules\Referrals\Traits;

use App\Modules\User\Models\User;
use Exception;
use Illuminate\Support\Str;

trait ReferralCodeService
{
    //user type prefix;
    private $userTypePrefixSet = [
            'superAdminPrefix' => "SA",
            'adminPrefix' => "A",
            'storePrefix' => "ST",
            'vendorPrefix' => "VE",
            'warehouseAdminPrefix' => "WA",
            'warehouseUserPrefix' => "WU",
            'salesManagerPrefix' => "SM"
          ];

    // province code numbers;
    private $userProvinceNumberSet = [
         'PRVN1Number' => 1,
         'PRVN2Number' => 2,
         'BGMTINumber' => 3,
         'GNDKINumber' => 4,
         'PRVN5Number' => 5,
         'KRNLINumber' => 6,
         'SPSCMNumber' => 7
     ];



    public function generateReferralCode(User $user){
        $userType = $user->userType->slug; // userTYpeSlug
        $userTypePrefix = $this->getUserPrefix($userType);
        $userProvinceNo = $this->getUserProvinceNo($user);
        $userFormatedCode = $this->getFormatedUserCode($user->user_code);
        return $this->referalCode($userTypePrefix,$userProvinceNo,$userFormatedCode);
    }

    private function referalCode($userTypePrefix,$userProvinceNo,$userFormatedCode){
        return $userTypePrefix.$userProvinceNo.$userFormatedCode;
    }

    private function getUserPrefix($userTYpe){
        $userTYpePrefix = Str::camel($userTYpe).'Prefix';
        try{
            $prop =  $this->userTypePrefixSet[$userTYpePrefix];
        }catch(Exception $exception){
            throw new Exception('No such user type exist');
        }
        return $prop;
    }

    private function getUserProvinceNo($user){

        $userType = $user->userTYpe->slug;
        $userProvince = '';
        switch($userType){
            case 'super-admin':
                 $userProvince = $user->ward_code;
                 break;
            case 'admin':
                $userProvince = $user->admin->ward->municipality->district->province->location_code;
                break;
            case 'vendor':
                $userProvince= $user->vendor->ward->municipality->district->province->location_code;
                break;
            case 'store':
                $userProvince= $user->store->ward->municipality->district->province->location_code;
                break;
            case 'warehouse-admin':
                $userProvince= $user->warehouseAdmin->ward->municipality->district->province->location_code;
                break;
            case 'warehouse-user':
                $userProvince= $user->warehouse->ward->municipality->district->province->location_code;
                break;
            case 'sales-manager':
                $userProvince= $user->manager->ward->municipality->district->province->location_code;
                break;
        }
        $userProvinceNumber = $userProvince.'Number';

        try{
            $provinceNumber =  $this->userProvinceNumberSet[$userProvinceNumber];
        }catch (Exception $exception){
            throw new Exception('No such province exist');
        }

        return $provinceNumber;
    }

    private function getFormatedUserCode($userCode){
        $userPrefix = 'U';
        $userSuffixNo= (int) str_replace($userPrefix,"",$userCode);
        return $userPrefix.$userSuffixNo;
    }


}
