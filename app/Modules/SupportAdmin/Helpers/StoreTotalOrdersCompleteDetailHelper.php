<?php


namespace App\Modules\SupportAdmin\Helpers;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StoreTotalOrdersCompleteDetailHelper
{

    public static function  getStoreOrderAndPreorder($filterParameters,$normalStatus,$preOrderStatus)
    {
        //dd($normalStatus,$preOrderStatus);
        try{
            $perPage = $filterParameters['perPage'];
            $query = self::getNewQueryForStoreOrderAndPreorder($filterParameters,$normalStatus,$preOrderStatus);
            $totalCount = count(DB::select($query));
            $offset = (($filterParameters['page'] - 1) * $perPage);
            if($perPage){
                $query .=  ' LIMIT '.$perPage. ' OFFSET '.$offset;
            }
            $results =  DB::select($query);
            $paginator = new LengthAwarePaginator($results, $totalCount, $perPage, $filterParameters['page'], ['path' => request()->url()]);
            return $paginator;
        }catch (\Exception $exception){
            throw $exception;
        }
    }

    public static function getNewQueryForStoreOrderAndPreorder($filterParameters,$normalStatus,$preOrderStatus){

        $query = "
                WITH storeNormalOrder AS (
                    select
                        store_order_code as order_code,
                        total_price,
                        acceptable_amount,
                        'normal_order' as order_type,
                        delivery_status as order_status,
                        created_at as order_created_at,
                        payment_status
                    from store_orders
                    where deleted_at is  null
                    AND store_code='".$filterParameters['store_code']."'
                    ";

                    if(isset($normalStatus) && count($normalStatus)>0){
                        $statusNormal = "'".implode("','",$normalStatus)."'";
                        $query .= ' AND delivery_status IN ('.$statusNormal.')';
                    }

                  $query .= "  ),

                    storePreorder As (
                        select
                            store_preorder_code as order_code,
                            total_price,
                            total_price as acceptable_amount,
                            'preorder' as order_type,
                            status as order_status,
                            created_at as order_created_at,
                            payment_status

                        from store_pre_orders_view
                        where deleted_at is  null
                        AND store_code= '".$filterParameters['store_code']."'
                    ";


                    if(isset($preOrderStatus) && count($preOrderStatus)>0){
                        $statusPreorder = "'".implode("','",$preOrderStatus)."'";
                        $query .= ' AND status IN ('.$statusPreorder.')';
                    }

                    $query .= " ),
                       storeOrderCompleteDetail As (
                            select * from storeNormalOrder
                            union
                            select * from storePreorder
                       )

             select * from storeOrderCompleteDetail where order_created_at IS NOT NULL
        ";

        if($filterParameters['order_date_from']){
            $query .= ' AND storeOrderCompleteDetail.order_created_at >= "'.$filterParameters['order_date_from'].'" ';
        }

        if($filterParameters['order_date_to']){
            $query .= ' AND storeOrderCompleteDetail.order_created_at <= "'.$filterParameters['order_date_to'].'" ';
        }
        if($filterParameters['order_type']){
            $query .= ' AND storeOrderCompleteDetail.order_type = "'.$filterParameters['order_type'].'" ';
        }
        if($filterParameters['order_code']){
            $query .= ' AND storeOrderCompleteDetail.order_code = "'.$filterParameters['order_code'].'" ';
        }

        if($filterParameters['payment_status']){
            $query .= ' AND storeOrderCompleteDetail.payment_status = "'.$filterParameters['payment_status'].'" ';
        }

        $query .= " ORDER BY storeOrderCompleteDetail.order_created_at DESC ";

        return $query;
    }

}
