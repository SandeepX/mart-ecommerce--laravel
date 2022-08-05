<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/20/2020
 * Time: 10:46 AM
 */

namespace App\Modules\Store\Helpers;


use App\Modules\Store\Models\StoreOrder;

class StoreOrderFilter
{

    public static function filterPaginatedStoreOrders($filterParameters,$paginateBy,$with=[]){



        $deliveryStatus =isset($filterParameters['delivery_status']) && (count(array_intersect($filterParameters['delivery_status'],StoreOrder::DELIVERY_STATUSES)) > 0) ? true:false;

        $paymentStatus =isset($filterParameters['payment_status']) && (count(array_intersect($filterParameters['payment_status'],['unpaid','pending', 'verified','rejected'])))? true:false;
      //  dd($filterParameters['delivery_status']);
        $priceCondition=isset($filterParameters['price_condition']) && in_array($filterParameters['price_condition'],['>','<', '>=','<=','='])? true:false;

        $storeOrders = StoreOrder::with($with)->when(isset($filterParameters['store_code']),function ($query) use($filterParameters){
            $query->where('store_code',$filterParameters['store_code']);
        })->when(isset($filterParameters['store_name']),function ($query) use($filterParameters){
            $query->whereHas('store', function ($query) use ($filterParameters) {
                $query->where('store_name','like','%'.$filterParameters['store_name'] . '%');
            });
        })->when(isset($filterParameters['store_order_code']),function ($query) use($filterParameters){
            $query->where('store_order_code','like','%'.$filterParameters['store_order_code'] . '%');
        })
        ->when($deliveryStatus,function ($query) use($filterParameters){
            $query->whereIn('delivery_status',$filterParameters['delivery_status']);
        })
        ->when($paymentStatus,function ($query) use($filterParameters){
           $unpaid = 0;
            if (in_array('unpaid',$filterParameters['payment_status'])){
                $unpaid = 1;
                $query->doesntHave('offlinePayments');
            }
            if($unpaid){
                $query->orWhereHas('offlinePayments', function ($query) use ($filterParameters) {
                    $query->whereRaw('id = (select max(id) from store_order_offline_payments where store_order_offline_payments.store_order_code = store_orders.store_order_code)')
                        ->whereIn('payment_status',$filterParameters['payment_status']);
                });
            }else{
                $query->whereHas('offlinePayments', function ($query) use ($filterParameters) {
                    $query->whereRaw('id = (select max(id) from store_order_offline_payments where store_order_offline_payments.store_order_code = store_orders.store_order_code)')
                        ->whereIn('payment_status',$filterParameters['payment_status']);
                });
            }


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

    public static function filterPaginatedStoreOrdersForAdmin($filterParameters,$paginateBy,$with=[]){
        $deliveryStatus =isset($filterParameters['delivery_status']) && in_array($filterParameters['delivery_status'],StoreOrder::DELIVERY_STATUSES) ? true:false;

//        $paymentStatus =isset($filterParameters['payment_status']) && in_array($filterParameters['payment_status'],['unpaid','pending', 'verified','rejected'])? true:false;
        $priceCondition=isset($filterParameters['price_condition']) && in_array($filterParameters['price_condition'],['>','<', '>=','<=','='])? true:false;



        $storeOrders = StoreOrder::with($with)->when(isset($filterParameters['store_code']),function ($query) use($filterParameters){
            $query->where('store_code',$filterParameters['store_code']);
        })->when(isset($filterParameters['store_name']),function ($query) use($filterParameters){
            $query->whereHas('store', function ($query) use ($filterParameters) {
                $query->where('store_name','like','%'.$filterParameters['store_name'] . '%');
            });
        })->when(isset($filterParameters['store_order_code']),function ($query) use($filterParameters){
            $query->where('store_order_code','like','%'.$filterParameters['store_order_code'] . '%');
        })
            ->when($deliveryStatus,function ($query) use($filterParameters){
                $query->where('delivery_status',$filterParameters['delivery_status']);
            })
            ->when(isset($filterParameters['warehouse_code']),function ($query) use($filterParameters){
                    $query->where('wh_code',$filterParameters['warehouse_code']);
            })
            ->when(isset($filterParameters['order_date_from']),function ($query) use($filterParameters){
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

    public static function getStoreOrdersForExcell($with=[])
    {
        $storeOrders = StoreOrder::with($with)->get();

        return $storeOrders;
    }
}
