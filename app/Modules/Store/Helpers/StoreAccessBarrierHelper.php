<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/3/2020
 * Time: 3:08 PM
 */

namespace App\Modules\Store\Helpers;


use App\Modules\Store\Models\Payments\StoreMiscellaneousPayment;

class StoreAccessBarrierHelper
{

    public static function canAuthStorePassBarrierConditions(){

        $authStoreCode = getAuthStoreCode();

        $verifiedKyc = self::isKycVerifiedForStore($authStoreCode);
        //$verifiedInitialRegistration = self::isInitialRegistrationVerifiedForStore($authStoreCode);
        $verifiedInitialRegistration = true;

        if ($verifiedKyc && $verifiedInitialRegistration){
            return true;
        }

        return false;
    }

    public static function isKycVerifiedForStore($storeCode){
        $isFirmKycVerified =FirmKycQueryHelper::isFirmKycVerifiedForStore($storeCode);
        $isSanchalakKycVerified = IndividualKycQueryHelper::isIndividualKycVerifiedForStore($storeCode,'sanchalak');
      //  $isAkthiyariKycVerified = IndividualKycQueryHelper::isIndividualKycVerifiedForStore($storeCode,'akhtiyari');

//        if ($isFirmKycVerified && ($isSanchalakKycVerified || $isAkthiyariKycVerified)){
//            return true;
//        }
        if ($isFirmKycVerified && $isSanchalakKycVerified){
            return true;
        }

        return false;
    }

    public static function isInitialRegistrationVerifiedForStore($storeCode){
        $verifiedInitialRegistrationCounts= StoreMiscellaneousPayment::where('store_code',$storeCode)
            ->where('payment_for','initial_registration')->where('verification_status','verified')->count();

        if ($verifiedInitialRegistrationCounts > 0){
            return true;
        }

        return false;
    }
}
