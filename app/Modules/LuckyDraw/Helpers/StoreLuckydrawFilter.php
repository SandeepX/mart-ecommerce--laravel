<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/22/2020
 * Time: 4:14 PM
 */

namespace App\Modules\LuckyDraw\Helpers;


use App\Modules\LuckyDraw\Models\StoreLuckydraw;
use App\Modules\LuckyDraw\Models\StoreLuckydrawWinner;
use App\Modules\Store\Models\PreOrder\StorePreOrderView;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\StoreOrder;
use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StoreLuckydrawFilter
{

    public static function filterPaginatedPrizes($filterParameters,$paginateBy,$with=[])
    {

        $prizes = StoreLuckydraw::with($with)
            ->select(
                'store_luckydraws.*'
            )
            ->when($filterParameters['luckydraw_name'], function ($query) use ($filterParameters) {
                $query->where('luckydraw_name', 'like', '%' . $filterParameters['luckydraw_name'] . '%');
            })->when($filterParameters['store_luckydraw_code'], function ($query) use ($filterParameters) {
                $query->where('store_luckydraw_code', '=', $filterParameters['store_luckydraw_code']);
            })->when($filterParameters['status'], function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['status']);
            })->when($filterParameters['type'], function ($query) use ($filterParameters) {
                $query->where('type', $filterParameters['type']);
            });
        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;
        $prizes = $prizes->latest()->paginate($paginateBy);
        return $prizes;
    }

    public static function getNotWinnerStores($storeLuckydrawDetail,$limit = 0,$random = false)
    {

//        $query =   "
//SELECT *,(purchase_eligibility * is_active * is_approved) as eligibility
//       FROM (SELECT
//    sd.store_code,
//    sd.store_name,
//    sd.store_full_location,
//    sd.store_logo,
//    COALESCE((so.total_price + spov.total_price), 0) AS total_purchased_price,
//    IF((SUM(so.total_price + spov.total_price) >= '.$storeLuckydrawDetail->eligibility_sales_amount.'),
//        1,
//        0) AS purchase_eligibility,
//    sd.is_active,
//    IF(sd.status = 'approved', 1, 0) AS is_approved
//FROM
//    stores_detail sd
//        LEFT JOIN
//    store_orders so ON so.store_code = sd.store_code
//        AND (so.delivery_status = 'dispatched'
//        AND so.updated_at >= DATE_SUB(CURDATE(), INTERVAL '.$storeLuckydrawDetail->days.' DAY))
//        LEFT JOIN
//    store_pre_orders_view spov ON spov.store_code = sd.store_code
//        AND (spov.status = 'dispatched'
//        AND spov.updated_at >= DATE_SUB(CURDATE(), INTERVAL '.$storeLuckydrawDetail->days.' DAY))
//WHERE
//    sd.store_code NOT IN (
//        SELECT
//            store_code
//        FROM
//            store_luckydraw_winners
//        WHERE
//                (MONTH(created_at) = MONTH('.$storeLuckydrawDetail->created_at.')
//                AND YEAR(created_at) = YEAR('.$storeLuckydrawDetail->created_at.'))
//                and winner_eligibility = 1
//        union
//        select store_code from store_luckydraw_winners
//       WHERE store_luckydraw_code = '.$storeLuckydrawDetail->store_luckydraw_code.'
//
//     )
//        AND sd.deleted_at IS NULL
//GROUP BY sd.store_code
//    ) dt";
//
//        if($random){
//            $query .= ' ORDER BY RAND()';
//        }
//       // dd($query);
//
//        if($limit > 0){
//            $query .= ' limit '.$limit.'';
//        }
//
//        $stores = DB::select(DB::raw($query));

        $query =   "
WITH storesDetails AS(
   SELECT
    store_code,
    store_name,
    store_full_location,
    store_logo,
    is_active,
    IF(status = 'approved', 1, 0) AS is_approved
    from stores_detail
    where deleted_at is NULL
),
storeNormlaOrderAmount AS(
    SELECT
    so.store_code,
    SUM(so.acceptable_amount) as normal_total
    from
    store_orders so
    where so.delivery_status = 'dispatched'
    AND so.updated_at >= DATE_SUB(CURDATE(), INTERVAL $storeLuckydrawDetail->days DAY)
    GROUP By so.store_code
),
storePreOrderAmount AS(
    SELECT
    spov.store_code,
    SUM(spov.total_price) as pre_order_amount
    from store_pre_orders_view spov
    where spov.status = 'dispatched'
    AND spov.updated_at >= DATE_SUB(CURDATE(), INTERVAL $storeLuckydrawDetail->days DAY)
    GROUP By spov.store_code
),
storeLuckyDrawInformation AS (select
sd.*,
(coalesce(normal_total,0)+ coalesce(pre_order_amount,0)) total_purchased_price
from
storesDetails sd
left join storeNormlaOrderAmount snoa on sd.store_code = snoa.store_code
left join storePreOrderAmount spoa on sd.store_code = spoa.store_code
WHERE
    sd.store_code NOT IN (
        SELECT
            store_code
        FROM
            store_luckydraw_winners
        WHERE
                (MONTH(created_at) = MONTH('$storeLuckydrawDetail->created_at')
                AND YEAR(created_at) = YEAR('$storeLuckydrawDetail->created_at'))
                and winner_eligibility = 1
        union
        select store_code from store_luckydraw_winners
       WHERE store_luckydraw_code = '$storeLuckydrawDetail->store_luckydraw_code'
     )
)
select
sldi.*,
(CASE WHEN sldi.total_purchased_price >= $storeLuckydrawDetail->eligibility_sales_amount THEN
1
ELSE
0
END) as  purchase_eligibility,
((sldi.total_purchased_price >=$storeLuckydrawDetail->eligibility_sales_amount) * is_active * is_approved) as eligibility
from
storeLuckyDrawInformation sldi;
     ";

        if($random){
            $query .= ' ORDER BY RAND()';
        }
        // dd($query);

        if($limit > 0){
            $query .= ' limit '.$limit.'';
        }

        $stores = DB::select(DB::raw($query));
        return $stores;
    }

    public static function selectRandomWinner($storeLuckydraw)
    {
        $cachedNotWinnerStores = Cache::get($storeLuckydraw->store_luckydraw_code,[]);
        if(count($cachedNotWinnerStores) > 0){
            $notWinnerStores = $cachedNotWinnerStores;
        }else{
            $notWinnerStores = StoreLuckydrawFilter::getNotWinnerStores($storeLuckydraw);
           // Cache::put($storeLuckydraw->store_luckydraw_code, $notWinnerStores);
        }
        $stores = collect($notWinnerStores);
        return $stores->whereNotIn('store_code',
            StoreLuckydrawWinner::where('store_luckydraw_code',$storeLuckydraw->store_luckydraw_code)
            ->where('winner_eligibility',0)
            ->pluck('store_code')
            ->toArray()
        )->random(1)->first();
    }

    public static function checkWinnerEligibility($store,$storeLuckydraw)
    {

          $storeOrderDispatch = StoreOrder::
               select(DB::raw('SUM(total_price) as total_price'))
               ->where('store_code',$store->store_code)
               ->where('delivery_status','dispatched')
               ->whereRaw("updated_at >= DATE(NOW()) - INTERVAL '.$storeLuckydraw->days.' DAY");
              // ->get();

        $totalDispatch = StorePreOrderView::
            select(DB::raw('SUM(total_price) as total_price'))
            ->where('store_code',$store->store_code)
            ->where('status','dispatched')
            ->whereRaw("updated_at >= DATE(NOW()) - INTERVAL '.$storeLuckydraw->days.' DAY")
            ->union($storeOrderDispatch)
             ->get();

        $totalDispatch = $totalDispatch->sum('total_price');

       return self::checkIfSalesDispatchMetEligibilitySales($totalDispatch,$storeLuckydraw->eligibility_sales_amount);

    }

    public static function checkIfSalesDispatchMetEligibilitySales($totalDispatch,$eligibilitySalesAmount)
    {
         if($totalDispatch >= $eligibilitySalesAmount)
         {
             return [
                 'eligibility'=>1,
                 'remarks'=>" ",
             ];
         }
         else{
             return [
                 'eligibility'=>0,
                 'remarks'=>"Store Sales Dispatch does not meet! ",
             ];
         }
    }

    public static function checkEligibilityForPrefix($store,$storeLuckydraw)
    {
        if($store->is_active === 1 && $store->status === 'approved')
        {
            $eligibility = self::checkWinnerEligibility($store,$storeLuckydraw);

            return $eligibility['eligibility'];
        }
        else {
            return 0;
        }
    }

    public static function paginate($results, $showPerPage)
    {
        $pageNumber = Paginator::resolveCurrentPage('page');

        $totalPageNumber = $results->count();

        return self::paginator($results->forPage($pageNumber, $showPerPage), $totalPageNumber, $showPerPage, $pageNumber, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  array  $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected static function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }
}
