<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/16/2020
 * Time: 5:23 PM
 */

namespace App\Modules\Store\Helpers;


use App\Modules\Store\Models\Kyc\IndividualKYCMaster;

class IndividualKycQueryHelper
{

    public static function isIndividualKycVerifiedForStore($storeCode,$kycFor){
        $individualKyc = IndividualKYCMaster::where('store_code',$storeCode)->where('kyc_for',$kycFor)->first();

        if ($individualKyc && $individualKyc->isVerified()){

            return true;
        }
        return false;
    }

    public static function canUpdateKyc($storeCode,$kycFor){
        $individualKyc = IndividualKYCMaster::where('store_code',$storeCode)->where('kyc_for',$kycFor)->first();

        if(!$individualKyc){
            return true; // can update
        }

        if ($individualKyc && $individualKyc->can_update_kyc){
            return true;
        }
        return false;
    }

    public static function filterIndividualKycByParameters(array $filterParameters,$with=[]){

        $verificationStatus =in_array($filterParameters['verification_status'],IndividualKYCMaster::VERIFICATION_STATUSES);

        $individualsKyc = IndividualKYCMaster::with($with)->when($filterParameters['kyc_type'],function ($query) use($filterParameters){
            $query-> where('kyc_for',$filterParameters['kyc_type']);
        })->when($verificationStatus,function ($query) use($filterParameters){
            $query-> where('verification_status',$filterParameters['verification_status']);
        })->latest()->get();

        return $individualsKyc;
    }

    public static function filterPaginatedIndividualKycByParameters(array $filterParameters,$paginateBy,$with=[]){

        $verificationStatus =isset($filterParameters['verification_status']) && in_array($filterParameters['verification_status'],IndividualKYCMaster::VERIFICATION_STATUSES) ? true:false;

        $individualsKyc = IndividualKYCMaster::with($with)
            ->when(isset($filterParameters['kyc_for']),function ($query) use($filterParameters){
            $query->where('kyc_for',$filterParameters['kyc_for']);
        })->when($verificationStatus,function ($query) use($filterParameters){
            $query-> where('verification_status',$filterParameters['verification_status']);
        })->when(isset($filterParameters['store_name']),function ($query) use($filterParameters){
                $query->whereHas('store', function ($query) use ($filterParameters) {
                    $query->where('store_name','like','%'.$filterParameters['store_name'] . '%');
                });
        })->when(isset($filterParameters['submit_date_from']),function ($query) use($filterParameters){
                $query->whereDate('created_at','>=',date('y-m-d',strtotime($filterParameters['submit_date_from'])));
        })->when(isset($filterParameters['submit_date_to']),function ($query) use($filterParameters){
                $query->whereDate('created_at','<=',date('y-m-d',strtotime($filterParameters['submit_date_to'])));
        });

        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

        $individualsKyc= $individualsKyc->latest()->paginate($paginateBy);

        return $individualsKyc;
    }
}
