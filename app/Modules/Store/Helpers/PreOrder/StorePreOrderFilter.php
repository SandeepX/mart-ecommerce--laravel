<?php


namespace App\Modules\Store\Helpers\PreOrder;


use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Models\PreOrder\StorePreOrderView;


use Illuminate\Support\Facades\DB;
class StorePreOrderFilter
{

    public static function filterPaginatedStorePreOrders($filterParameters, $paginateBy, $with = [])
    {

        $status =isset($filterParameters['status']) && in_array($filterParameters['status'],
            StorePreOrder::STATUSES) ? true:false;
        $vat_percent = 1+ (StorePreOrder::VAT_PERCENTAGE_VALUE/100);

        $storePreOrders = StorePreOrder::with($with)
            ->when(isset($filterParameters['warehouse_preorder_listing_code']), function ($query) use ($filterParameters) {
                $query->where('store_preorder.warehouse_preorder_listing_code', $filterParameters['warehouse_preorder_listing_code']);
            })->when(isset($filterParameters['warehouse_code']), function ($query) use ($filterParameters) {
                $query->whereHas('warehousePreOrderListing', function ($query) use ($filterParameters) {
                    $query->where('warehouse_preorder_listings.warehouse_code',$filterParameters['warehouse_code']);
                });
            })
            ->when(isset($filterParameters['store_code']), function ($query) use ($filterParameters) {
                $query->where('store_preorder.store_code', $filterParameters['store_code']);
            })->when(isset($filterParameters['store_name']), function ($query) use ($filterParameters) {
                $query->whereHas('store', function ($query) use ($filterParameters) {
                    $query->where('stores_detail.store_name', 'like', '%' . $filterParameters['store_name'] . '%');
                });
            })->when(isset($filterParameters['store_preorder_code']), function ($query) use ($filterParameters) {
                $query->where('store_preorder.store_preorder_code', 'like', '%' . $filterParameters['store_preorder_code'] . '%');
            })->when(isset($filterParameters['payment_status']), function ($query) use ($filterParameters) {
                $query->where('store_preorder.payment_status', $filterParameters['payment_status']);
            }) ->when($status,function ($query) use($filterParameters){
                $query->where('store_preorder.status',$filterParameters['status']);
            })->when(isset($filterParameters['order_date_from']),function ($query) use($filterParameters){
                $query->whereDate('store_preorder.created_at','>=',date('y-m-d',strtotime($filterParameters['order_date_from'])));
            })->when(isset($filterParameters['order_date_to']),function ($query) use($filterParameters){
                $query->whereDate('store_preorder.created_at','<=',date('y-m-d',strtotime($filterParameters['order_date_to'])));
            }) ->leftJoin('store_preorder_details as t2',function ($join){
                $join->on( 't2.store_preorder_code',
                    '=',
                    'store_preorder.store_preorder_code'
                )->whereNull('t2.deleted_at');
            }
               )
            ->leftJoin('warehouse_preorder_products as t1',
                't1.warehouse_preorder_product_code',
                '=',
                't2.warehouse_preorder_product_code')
            ->select(
                'store_preorder.store_preorder_code',
                'store_preorder.warehouse_preorder_listing_code',
                'store_preorder.store_code',
                'store_preorder.payment_status',
                'store_preorder.status',
                'store_preorder.created_at'
            )
            ->addSelect(DB::raw('
                                 ROUND(SUM(
                                       Case t2.is_taxable when "1" Then
                                        (
                                            (
                                                 t1.mrp
                                                      -
                                                      (
                                                          CASE t1.wholesale_margin_type when "p"
                                                          Then
                                                              (t1.wholesale_margin_value/100)*t1.mrp
                                                          Else
                                                              t1.wholesale_margin_value End
                                                      )
                                                      -
                                                      (
                                                          CASE t1.retail_margin_type when "p"
                                                          Then
                                                              (t1.retail_margin_value/100)*mrp
                                                          Else
                                                              t1.retail_margin_value End
                                                      )
                                             )/'.$vat_percent.'
                                              ) * t2.quantity
                                             ELSE
                                         (
                                             t1.mrp
                                                  -
                                                  (
                                                      CASE t1.wholesale_margin_type when "p"
                                                      Then
                                                          (t1.wholesale_margin_value/100)*t1.mrp
                                                      Else
                                                          t1.wholesale_margin_value End
                                                  )
                                                  -
                                                  (
                                                      CASE t1.retail_margin_type when "p"
                                                      Then
                                                          (t1.retail_margin_value/100)*mrp
                                                      Else
                                                          t1.retail_margin_value End
                                                  )
                                         ) * t2.quantity
                                         END
                                   ),2)
                                  as total_price
                               '));

        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

        $storePreOrders= $storePreOrders->groupBy('store_preorder.store_preorder_code')->orderBy('store_preorder.created_at','DESC')->paginate($paginateBy);
       // dd($storePreOrders);
        return $storePreOrders;
    }

    public static function newfilterPaginatedStorePreOrders($filterParameters, $paginateBy, $with = [])
    {

        $status =isset($filterParameters['status']) && in_array($filterParameters['status'],
            StorePreOrder::STATUSES) ? true:false;
        $vat_percent = 1+ (StorePreOrder::VAT_PERCENTAGE_VALUE/100);

        $storePreOrders = StorePreOrderView::with($with)
            ->when(isset($filterParameters['warehouse_preorder_listing_code']), function ($query) use ($filterParameters) {
                $query->where('store_pre_orders_view.warehouse_preorder_listing_code', $filterParameters['warehouse_preorder_listing_code']);
            })->when(isset($filterParameters['warehouse_code']), function ($query) use ($filterParameters) {
                $query->whereHas('warehousePreOrderListing', function ($query) use ($filterParameters) {
                    $query->where('warehouse_preorder_listings.warehouse_code',$filterParameters['warehouse_code']);
                });
            })
            ->when(isset($filterParameters['store_code']), function ($query) use ($filterParameters) {
                $query->where('store_pre_orders_view.store_code', $filterParameters['store_code']);
            })->when(isset($filterParameters['store_name']), function ($query) use ($filterParameters) {
                $query->whereHas('store', function ($query) use ($filterParameters) {
                    $query->where('stores_detail.store_name', 'like', '%' . $filterParameters['store_name'] . '%');
                });
            })->when(isset($filterParameters['store_preorder_code']), function ($query) use ($filterParameters) {
                $query->where('store_pre_orders_view.store_preorder_code', 'like', '%' . $filterParameters['store_preorder_code'] . '%');
            })->when(isset($filterParameters['payment_status']), function ($query) use ($filterParameters) {
                $query->where('store_pre_orders_view.payment_status', $filterParameters['payment_status']);
            }) ->when($status,function ($query) use($filterParameters){
                $query->where('store_pre_orders_view.status',$filterParameters['status']);
            })->when(isset($filterParameters['order_date_from']),function ($query) use($filterParameters){
                $query->whereDate('store_pre_orders_view.created_at','>=',date('y-m-d',strtotime($filterParameters['order_date_from'])));
            })->when(isset($filterParameters['order_date_to']),function ($query) use($filterParameters){
                $query->whereDate('store_pre_orders_view.created_at','<=',date('y-m-d',strtotime($filterParameters['order_date_to'])));
            })
            ->join('stores_detail','stores_detail.store_code','=','store_pre_orders_view.store_code')
            ->select(
                'store_pre_orders_view.store_preorder_code',
                'store_pre_orders_view.warehouse_preorder_listing_code',
                'store_pre_orders_view.store_code',
                'store_pre_orders_view.payment_status',
                'store_pre_orders_view.status',
                'store_pre_orders_view.created_at',
                'store_pre_orders_view.total_price',
                'store_pre_orders_view.early_finalized'

            );


        $paginateBy = isset($filterParameters['records_per_page'])  ? $filterParameters['records_per_page'] : $paginateBy;

        $storePreOrders= $storePreOrders->groupBy('store_pre_orders_view.store_preorder_code')->orderBy('store_pre_orders_view.created_at','DESC')->paginate($paginateBy);
       //  dd($storePreOrders);
        return $storePreOrders;
    }


}
