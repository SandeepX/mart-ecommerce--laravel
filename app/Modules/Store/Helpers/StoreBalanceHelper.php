<?php


namespace App\Modules\Store\Helpers;


use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use Illuminate\Support\Facades\DB;

class StoreBalanceHelper
{
    public static function filterPaginatedStoresForAdmin($filterParameters,$paginateBy=null,$with=[])
    {

        $creditTypes = implode(',',StoreBalanceMaster::CREDIT_TYPES);
        $storeBalances = StoreBalanceMaster::rightJoin('stores_detail',function($join){
               $join->on('stores_detail.store_code','=','store_balance_master.store_code');
            })
            ->where('stores_detail.status','approved')
            ->groupBy('stores_detail.store_code')
            ->select(
                'stores_detail.store_name',
                'stores_detail.store_code',
                'stores_detail.status'
            )
            ->addSelect(DB::raw('max(store_balance_master.created_at) as last_transaction_date'))
//            ->addSelect([
//                'store_current_balance' => StoreBalanceMaster::select('current_balance')
//                ->whereColumn('store_code','stores_detail.store_code')
//                ->latest('id')
//                ->limit(1)
//            ])
            ->addSelect(DB::raw('
round(sum( case when ( FIND_IN_SET(transaction_type,"'.$creditTypes.'"))
then round((transaction_amount),2)
else round((-1 * transaction_amount),2)
end),2)
    as store_current_balance
            '))
            ->when(isset($filterParameters['store_name']),function($query) use ($filterParameters){
                $query->where('stores_detail.store_name','LIKE','%'.$filterParameters['store_name'].'%');
            })->when(isset($filterParameters['current_balance_order']),function($query) use ($filterParameters){
                if($filterParameters['current_balance_order']=="high_to_low")
                {
                    $query->orderBy('store_current_balance','DESC');
                }
                elseif($filterParameters['current_balance_order']=="low_to_high")
                {
                    $query->orderBy('store_current_balance','ASC');
                }
            })->when($filterParameters['province'], function ($query) use ($filterParameters) {

                $query->whereHas('store.location.municipality.district.province', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['province']);
                });
            })->when($filterParameters['district'], function ($query) use ($filterParameters) {

                $query->whereHas('store.location.municipality.district', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['district']);
                });
            })->when($filterParameters['municipality'], function ($query) use ($filterParameters) {

                $query->whereHas('store.location.municipality', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['municipality']);
                });
            })->when($filterParameters['ward'], function ($query) use ($filterParameters) {

                $query->whereHas('store.location', function ($query) use ($filterParameters) {
                    $query->where('location_code', $filterParameters['ward']);
                });
            });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $storeBalanceQuery = $storeBalances->orderBy('store_balance_master.id','DESC');
        return is_null($paginateBy) ?$storeBalanceQuery->get() : $storeBalanceQuery->paginate($paginateBy);
    }
}
