<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/22/2020
 * Time: 4:14 PM
 */

namespace App\Modules\SalesManager\Helpers;

use App\Modules\ManagerDiary\Models\Diary\ManagerDiary;
use App\Modules\SalesManager\Models\Manager;
use App\Modules\SalesManager\Models\ManagerStore;
use App\Modules\SalesManager\Models\ManagerStoreReferral;
use App\Modules\Store\Models\Store;
use Illuminate\Support\Facades\DB;
use function React\Promise\all;

class SalesManagerStoreLoactionsFilter
{
    public static function filterStoreLocation($filterParameters)
    {

        $managerToStoreReferrals = ManagerStoreReferral::select(
            'manager_store_referrals.referred_store_code  as manager_entity_code',
            'stores_detail.store_name as store_name',
            DB::raw('"referrals" as filter_type'),
            DB::raw('stores_detail.latitude as latitude'),
            DB::raw('stores_detail.longitude as longitude'),
            'stores_detail.store_full_location as location'
        )->join('stores_detail', 'stores_detail.store_code', '=', 'manager_store_referrals.referred_store_code')
            ->when(isset($filterParameters['manager_code']), function ($query) use ($filterParameters) {

                $query->where('manager_store_referrals.manager_code', $filterParameters['manager_code']);
            })
            ->when($filterParameters['filter_type'], function ($query) use ($filterParameters) {

                $query->having('filter_type', $filterParameters['filter_type']);
            })->whereNotNull('stores_detail.latitude');


        $managerToDairy = ManagerDiary::select(
            'manager_diaries.referred_store_code as manager_entity_code',
            'manager_diaries.store_name as store_name',
            DB::raw('"diary" as filter_type'),
            'manager_diaries.latitude as latitude',
            'manager_diaries.longitude as longitude',
            'full_location as location'

        )
            ->when(isset($filterParameters['manager_code']), function ($query) use ($filterParameters) {
                $query->where('manager_diaries.manager_code', $filterParameters['manager_code']);
            })
            ->when($filterParameters['filter_type'], function ($query) use ($filterParameters) {
                $query->having('filter_type', $filterParameters['filter_type']);
            })->whereNotNull('manager_diaries.longitude')
            ->union($managerToStoreReferrals)->get();
        return $managerToDairy;

    }
}
