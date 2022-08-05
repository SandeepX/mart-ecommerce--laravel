<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/22/2020
 * Time: 4:14 PM
 */

namespace App\Modules\SalesManager\Helpers;

use App\Modules\SalesManager\Models\Manager;
use App\Modules\SalesManager\Models\ManagerStoreReferral;
use App\Modules\SalesManager\Models\ManagerToManagerReferrals;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\DB;

class SalesManagerFilter
{

    public static function filterPaginatedSalesManager($filterParameters,$paginateBy,$with=[])
    {

        $salesManager = Manager::with($with)
            ->when(isset($filterParameters['name']),function ($query) use ($filterParameters){
                $query->where('manager_name' ,'like', '%' . $filterParameters['name'] . '%');
            })
            ->when(isset($filterParameters['status']),function ($query) use ($filterParameters){
                $query->where('status',$filterParameters['status']);
            })
            ->when($filterParameters['province'], function ($query) use ($filterParameters) {

                $query->whereHas('ward.municipality.district.province', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['province']);
                });
            })->when($filterParameters['district'], function ($query) use ($filterParameters) {

                $query->whereHas('ward.municipality.district', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['district']);
                });
            })->when($filterParameters['municipality'], function ($query) use ($filterParameters) {

                $query->whereHas('ward.municipality', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['municipality']);
                });
            })->when($filterParameters['ward'], function ($query) use ($filterParameters) {

                    $query->whereHas('ward', function ($query) use ($filterParameters) {
                        $query->where('location_code', $filterParameters['ward']);
                    });
            })
            ->when($filterParameters['temporary_province'], function ($query) use ($filterParameters) {

                $query->whereHas('temporaryLocation.municipality.district.province', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['temporary_province']);
                });
            })->when($filterParameters['temporary_district'], function ($query) use ($filterParameters) {

                $query->whereHas('temporaryLocation.municipality.district', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['temporary_district']);
                });
            })->when($filterParameters['temporary_municipality'], function ($query) use ($filterParameters) {

                $query->whereHas('temporaryLocation.municipality', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['temporary_municipality']);
                });
            })->when($filterParameters['temporary_ward'], function ($query) use ($filterParameters) {

                $query->whereHas('temporaryLocation', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['temporary_ward']);
                });
            });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $salesManager = $salesManager->latest()->paginate($paginateBy);
        return $salesManager;
    }

    public static function checkManagerReferralCodeExists($referralCode,$managerCode){
        $managerReferralData = Manager::where('referral_code',$referralCode)
            ->where('manager_code','!=',$managerCode)->get();
       // dd($userReferalData);
        if(count($managerReferralData)>0){
            return true;
        }
        return false;
    }

    public static function filterPaginatedManagersReferrals($filterParameters , $paginateBy = 10){

        $managerToStoreReferrals = ManagerStoreReferral::select(
                                                'manager_store_referrals.referred_store_code  as user_entity_code',
                                                'stores_detail.store_name as user_name',
                                                 DB::raw('"store" as user_type'),
                                                'stores_detail.store_location_code as location_code',
                                                'stores_detail.store_contact_phone as phone_number',
                                                'manager_store_referrals.created_at'
                                     )
                                    ->join('stores_detail','stores_detail.store_code','=','manager_store_referrals.referred_store_code')
                                    ->when(isset($filterParameters['manager_code']),function ($query) use ($filterParameters){
                                          $query->where('manager_store_referrals.manager_code',$filterParameters['manager_code']);
                                    })
                                    ->when($filterParameters['user_name'],function ($query) use ($filterParameters){
                                        $query->where('stores_detail.store_name','LIKE','%'.$filterParameters['user_name'].'%');
                                    })
                                    ->when($filterParameters['user_type'],function ($query) use ($filterParameters){
                                        $query->having('user_type',$filterParameters['user_type']);
                                    })
                                    ->when($filterParameters['date_from'],function ($query) use ($filterParameters){
                                        $query->where('manager_store_referrals.created_at','>=',$filterParameters['date_from']);
                                    })
                                    ->when($filterParameters['date_to'],function ($query) use ($filterParameters){
                                        $query->where('manager_store_referrals.created_at','<=',$filterParameters['date_to']);
                                    })
                                    ->when($filterParameters['phone_number'],function ($query) use ($filterParameters){
                                        $query->where('stores_detail.store_contact_phone',$filterParameters['phone_number']);
                                    });

        $managerToManagerReferrals = ManagerToManagerReferrals::select(
                                                'manager_to_manager_referrals.referred_manager_code as user_entity_code',
                                                'managers_detail.manager_name as user_name',
                                                 DB::raw('"manager" as user_type'),
                                                'managers_detail.permanent_ward_code as location_code',
                                                'managers_detail.manager_phone_no as phone_number',
                                                'manager_to_manager_referrals.created_at'
                                       )
                                       ->join('managers_detail',
                                              'managers_detail.manager_code',
                                               '=',
                                               'manager_to_manager_referrals.referred_manager_code'
                                       )->when(isset($filterParameters['manager_code']),function ($query) use ($filterParameters){
                                                $query->where('manager_to_manager_referrals.manager_code',$filterParameters['manager_code']);
                                       })
                                        ->when($filterParameters['user_name'],function ($query) use ($filterParameters){
                                            $query->where('managers_detail.manager_name','LIKE','%'.$filterParameters['user_name'].'%');
                                        })
                                        ->when($filterParameters['user_type'],function ($query) use ($filterParameters){
                                            $query->having('user_type',$filterParameters['user_type']);
                                        })
                                        ->when($filterParameters['date_from'],function ($query) use ($filterParameters){
                                            $query->where('manager_to_manager_referrals.created_at','>=',$filterParameters['date_from']);
                                        })
                                        ->when($filterParameters['date_to'],function ($query) use ($filterParameters){
                                            $query->where('manager_to_manager_referrals.created_at','<=',$filterParameters['date_to']);
                                        })
                                        ->when($filterParameters['phone_number'],function ($query) use ($filterParameters){
                                            $query->having('managers_detail.manager_phone_no',$filterParameters['phone_number']);
                                        })
                                       ->unionAll($managerToStoreReferrals);


        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;
        $managerToManagerReferrals =  $managerToManagerReferrals->orderBy('created_at','DESC')->paginate($paginateBy);

        return $managerToManagerReferrals;
    }
}
