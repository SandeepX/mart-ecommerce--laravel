<?php

namespace App\Modules\AlpasalWarehouse\Helpers\Store;


use App\Modules\Store\Models\StoreOrder;
use Illuminate\Support\Facades\DB;

class WHStoreOrderFilter
{
    public static function filterWHStoreOrdersByStore($filterParameters, $paginateBy) {
        $deliveryStatus = isset($filterParameters['delivery_status']) &&
        in_array(
            $filterParameters['delivery_status'],
            StoreOrder::DELIVERY_STATUSES
        ) ? true : false;
        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;
        $storeOrder = StoreOrder::where('wh_code',getAuthWarehouseCode())
            ->join('stores_detail', 'store_orders.store_code', '=', 'stores_detail.store_code')
            ->select(
                DB::raw('max(store_orders.id) as last_order_id'),
                'store_orders.store_code',
                'stores_detail.store_name'
            )
            ->addSelect(DB::raw('count(store_orders.store_code) as total_orders'))
            ->groupBy('store_orders.store_code');

        $storeOrders = DB::table('store_orders')
            ->select(
                'store_od.store_code',
                'store_od.store_name',
                'store_od.total_orders',
                'store_orders.created_at',
                'store_orders.delivery_status'
            )
            ->joinSub($storeOrder, 'store_od', function ($join) {
                $join->on('store_orders.id', '=', 'store_od.last_order_id');
            })
            ->when($deliveryStatus, function ($query) use($filterParameters){
                $query->where('store_orders.delivery_status',$filterParameters['delivery_status']);
            })
            ->when(isset($filterParameters['store_name_code']), function ($query) use ($filterParameters) {
                $query->where('store_od.store_code', 'like', '%'.$filterParameters['store_name_code'].'%')
                    ->orWhere('store_od.store_name', 'like', '%'.$filterParameters['store_name_code'].'%');
            })
            ->when(isset($filterParameters['order_date_from']),function ($query) use($filterParameters){
                $query->whereDate('store_orders.created_at','>=',date('y-m-d',strtotime($filterParameters['order_date_from'])));
            })->when(isset($filterParameters['order_date_to']),function ($query) use($filterParameters){
                $query->whereDate('store_orders.created_at','<=',date('y-m-d',strtotime($filterParameters['order_date_to'])));
            })
            ->orderBy('store_orders.created_at','DESC')
            ->latest()->paginate($paginateBy);
        return $storeOrders;
    }

    public static function filterWHStoreOrdersByStoreCode($filterParameters, $storeCode, $paginateBy,$with=[]){
        $deliveryStatus =isset($filterParameters['delivery_status']) && in_array($filterParameters['delivery_status'],StoreOrder::DELIVERY_STATUSES) ? true:false;
        $paymentStatus =isset($filterParameters['payment_status']) && in_array($filterParameters['payment_status'],['unpaid','pending', 'verified','rejected'])? true:false;
        $priceCondition=isset($filterParameters['price_condition']) && in_array($filterParameters['price_condition'],['>','<', '>=','<=','='])? true:false;

        $storesConnectedToAuthWH = StoreWarehouseHelper::getStoresConnectedWithWarehouse(getAuthWarehouseCode());

        $storeOrders = StoreOrder::where('wh_code',getAuthWarehouseCode())
            ->where('store_code', $storeCode)
            ->with($with)
            ->when(isset($filterParameters['store_order_code']),function ($query) use($filterParameters){
                $query->where('store_order_code','like','%'.$filterParameters['store_order_code'] . '%');
            })
            ->when($deliveryStatus,function ($query) use($filterParameters){
                $query->where('delivery_status',$filterParameters['delivery_status']);
            })
            ->when($paymentStatus,function ($query) use($filterParameters){
                if ($filterParameters['payment_status'] == 'unpaid'){
                    return $query->doesntHave('offlinePayments');
                }

                $query->whereHas('offlinePayments', function ($query) use ($filterParameters) {
                    $query->whereRaw('id = (select max(id) from store_order_offline_payments where store_order_offline_payments.store_order_code = store_orders.store_order_code)')
                        ->where('payment_status',$filterParameters['payment_status']);
                });

            })->when(isset($filterParameters['order_date_from']),function ($query) use($filterParameters){
                $query->whereDate('created_at','>=',date('y-m-d',strtotime($filterParameters['order_date_from'])));
            })->when(isset($filterParameters['order_date_to']),function ($query) use($filterParameters){
                $query->whereDate('created_at','<=',date('y-m-d',strtotime($filterParameters['order_date_to'])));
            })->when($priceCondition && isset($filterParameters['total_price']),function ($query) use($filterParameters){
                $query->where('total_price',$filterParameters['price_condition'],$filterParameters['total_price']);
            })->when(isset($filterParameters['payable_price_from']),function ($query) use($filterParameters){
                $query->where('acceptable_amount','>=',$filterParameters['payable_price_from']);
            })->when(isset($filterParameters['payable_price_to']),function ($query) use($filterParameters){
                $query->where('acceptable_amount','<=',$filterParameters['payable_price_to']);
            });


        //for global search
        $storeOrders= $storeOrders->when(isset($filterParameters['global_search_keyword']),function ($q) use($filterParameters){

            $q->where(function ($query) use ($filterParameters) {
                $query->where('store_order_code','like','%'.$filterParameters['global_search_keyword'] . '%')
                    ->orWhere('delivery_status','like','%'.$filterParameters['global_search_keyword'] . '%')
                    ->orWhereDate('created_at',date('y-m-d',strtotime($filterParameters['global_search_keyword'])))
                    ->orWhere('total_price','like','%'.$filterParameters['global_search_keyword'] . '%');
            });
        });

        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

        $storeOrders= $storeOrders->latest()->paginate($paginateBy);
        return $storeOrders;
    }
}
