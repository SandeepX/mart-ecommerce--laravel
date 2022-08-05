<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/22/2020
 * Time: 4:14 PM
 */

namespace App\Modules\Store\Helpers;


use App\Modules\Location\Models\LocationHierarchy;
use App\Modules\Store\Models\Kyc\FirmKycMaster;
use App\Modules\Store\Models\Kyc\IndividualKYCMaster;
use App\Modules\Store\Models\Store;
use Illuminate\Support\Facades\DB;

class StoreKycListingHelper
{

    public static function filterKycListing($filterParameters,$paginateBy,$with=[])
    {

        $kycTypes=IndividualKYCMaster::KYC_FOR_TYPES;
        $verification_status = IndividualKYCMaster::VERIFICATION_STATUSES;

        $firm = FirmKycMaster::select(
            'firm_kyc_master.id as firm_latest_id',
            'firm_kyc_master.kyc_code as firm_kyc_code',
            'firm_kyc_master.updated_at as firm_latest_updated_at',
            'firm_kyc_master.store_code as firm_store_code',
            'firm_kyc_master.verification_status as firm_last_verification_status'
        )->whereIn('id',function ($query){
            $query->select(DB::raw('MAX(id)'))
                ->groupBY('firm_kyc_master.store_code');
        });

        $sanchalak = IndividualKYCMaster::select(
            'individual_kyc_master.id as sanchalak_latest_id',
            'individual_kyc_master.kyc_code as sanchalak_kyc_code',
            'individual_kyc_master.updated_at as sanchalak_latest_updated_at',
            'individual_kyc_master.store_code as sanchalak_store_code',
            'individual_kyc_master.verification_status as sanchalak_last_verification_status'
        )
            ->whereIn('id',function ($query){
                $query->select(DB::raw('MAX(id)'))
                    ->where('individual_kyc_master.kyc_for','sanchalak')
                    ->groupBy('individual_kyc_master.store_code');
            });

        $akhtiyari = IndividualKYCMaster::select(
            'individual_kyc_master.id as akhtiyari_latest_id',
            'individual_kyc_master.kyc_code as akhtiyari_kyc_code',
            'individual_kyc_master.updated_at as akhtiyari_latest_updated_at',
            'individual_kyc_master.store_code as akhtiyari_store_code',
            'individual_kyc_master.verification_status as akhtiyari_last_verification_status'
        )
            ->whereIn('id',function ($query){
                $query->select(DB::raw('MAX(id)'))
                    ->where('individual_kyc_master.kyc_for','akhtiyari')
                    ->groupBy('individual_kyc_master.store_code');
            });

        $kycListings = Store::select(
            'stores_detail.store_code',
            'stores_detail.store_name',
            'firm_table.firm_latest_id',
            'firm_table.firm_kyc_code',
            'firm_table.firm_latest_updated_at',
            'firm_table.firm_last_verification_status',
            'sanchalak_table.sanchalak_latest_id',
            'sanchalak_table.sanchalak_kyc_code',
            'sanchalak_table.sanchalak_latest_updated_at',
            'sanchalak_table.sanchalak_last_verification_status',
            'akhtiyari_table.akhtiyari_latest_id',
            'akhtiyari_table.akhtiyari_kyc_code',
            'akhtiyari_table.akhtiyari_latest_updated_at',
            'akhtiyari_table.akhtiyari_last_verification_status'
           )
            ->leftJoinSub($firm,'firm_table',function ($join){
                $join->on('stores_detail.store_code','=','firm_table.firm_store_code');
            })
            ->leftJoinSub($sanchalak,'sanchalak_table',function ($join){
                $join->on('stores_detail.store_code','=','sanchalak_table.sanchalak_store_code');
            })
            ->leftJoinSub($akhtiyari,'akhtiyari_table',function ($join){
                $join->on('stores_detail.store_code','=','akhtiyari_table.akhtiyari_store_code');
            })
            ->when(isset($filterParameters['store_name']),function ($query) use($filterParameters) {
                $query->where('store_name', 'like', '%' . $filterParameters['store_name'] . '%');
            })
            ->where(function ($query) use ($filterParameters,$kycTypes){
                 if(isset($filterParameters['kyc_for']) && isset($filterParameters['verification_status'])){
                     if(in_array('firm', $filterParameters['kyc_for'])){
                         $query->whereNotNull('firm_latest_id')
                             ->where('firm_last_verification_status',$filterParameters['verification_status']);
                     }
                     if(in_array('sanchalak',$filterParameters['kyc_for'])){
                         $query->whereNotNull('sanchalak_latest_id')
                             ->where('sanchalak_last_verification_status',$filterParameters['verification_status']);
                     }
                     if(in_array('akhtiyari',$filterParameters['kyc_for'])){
                         $query->whereNoTNull('akhtiyari_latest_id')
                             ->where('akhtiyari_last_verification_status',$filterParameters['verification_status']);
                     }
                 }
                 elseif(isset($filterParameters['kyc_for'])){
                     if(in_array('firm', $filterParameters['kyc_for'])){
                         $query->whereNotNull('firm_latest_id');
                     }
                     if(in_array('sanchalak',$filterParameters['kyc_for'])){
                         $query->whereNotNull('sanchalak_latest_id');
                     }
                     if(in_array('akhtiyari',$filterParameters['kyc_for'])){
                         $query->whereNoTNull('akhtiyari_latest_id');
                     }
                 }
                 elseif(isset($filterParameters['verification_status'])){
                     $query->where('firm_last_verification_status',$filterParameters['verification_status'])
                         ->orWhere('sanchalak_last_verification_status',$filterParameters['verification_status'])
                         ->orWhere('akhtiyari_last_verification_status',$filterParameters['verification_status']);
                 }
                 else{
                     $query->has('individualKyc')->orHas('firmKyc');
                 }
            })
            ->addSelect(DB::raw('GREATEST(
                        ifnull(firm_latest_updated_at,"0000-00-00 00:00:00"),
                        ifnull(sanchalak_latest_updated_at,"0000-00-00 00:00:00"),
                        ifnull(akhtiyari_latest_updated_at,"0000-00-00 00:00:00")) as greatest'
            )
            )
            ->orderBy('greatest','DESC')
            ->paginate($paginateBy);

       return $kycListings;
    }
}
