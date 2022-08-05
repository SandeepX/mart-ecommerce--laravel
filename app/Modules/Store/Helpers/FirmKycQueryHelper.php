<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/16/2020
 * Time: 11:08 AM
 */

namespace App\Modules\Store\Helpers;

use App\Modules\Store\Models\Kyc\FirmKycDocument;
use App\Modules\Store\Models\Kyc\FirmKycMaster;
use Exception;

class FirmKycQueryHelper
{

    public static function isFirmKycVerifiedForStore($storeCode){
        $firmKyc = FirmKycMaster::where('store_code',$storeCode)->first();

        if ($firmKyc && $firmKyc->isVerified()){

            return true;
        }
        return false;
    }

    public static function canUpdateKyc($storeCode){
        $firmKyc = FirmKycMaster::where('store_code',$storeCode)->first();

        if(!$firmKyc){
            return true; // can update
        }

        if ($firmKyc && $firmKyc->can_update_kyc){
            return true;
        }
        return false;
    }

    public static function getAuthStoreExistingDocumentTypes(){
        try{
            $firmKycDocumentTypes=[];
            $storeCode = getAuthStoreCode();

            $firmKycMaster = FirmKycMaster::where('store_code',$storeCode)->first();

            if ($firmKycMaster){
               $firmKycDocumentTypes = FirmKycDocument::where('kyc_code',$firmKycMaster->kyc_code)->pluck('document_type')->toArray();
            }

            return $firmKycDocumentTypes;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public static function filterFirmKycByParameters(array $filterParameters,$with=[]){

        $verificationStatus =in_array($filterParameters['verification_status'],FirmKycMaster::VERIFICATION_STATUSES);

        $firmsKyc = FirmKycMaster::with($with)->when($filterParameters['business_registered_from'],function ($query) use($filterParameters){
            $query-> where('business_registered_from',$filterParameters['business_registered_from']);
        })->when($verificationStatus,function ($query) use($filterParameters){
            $query-> where('verification_status',$filterParameters['verification_status']);
        })->latest()->get();

        return $firmsKyc;
    }

    public static function filterPaginatedFirmKycByParameters(array $filterParameters,$paginateBy,$with=[]){

        $verificationStatus =isset($filterParameters['verification_status']) && in_array($filterParameters['verification_status'],FirmKycMaster::VERIFICATION_STATUSES) ? true:false;

        $firmsKyc = FirmKycMaster::with($with)
            ->when(isset($filterParameters['business_registered_from']),function ($query) use($filterParameters){
                $query-> where('business_registered_from',$filterParameters['business_registered_from']);
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

        $firmsKyc= $firmsKyc->latest()->paginate($paginateBy);
        return $firmsKyc;
    }
}
