<?php

namespace App\Modules\Store\Helpers\PreOrder;


use App\Modules\AlpasalWarehouse\Helpers\Store\StoreWarehouseHelper;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderListing;
use App\Modules\AlpasalWarehouse\Models\PreOrder\WarehousePreOrderTarget;
use App\Modules\Product\Models\ProductMaster;
use App\Modules\Store\Models\PreOrder\StorePreOrder;
use App\Modules\Store\Models\PreOrder\StorePreOrderDetail;
use App\Modules\Store\Models\PreOrder\StorePreOrderView;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\StoreOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StorePreOrderHelper
{

    //usage in single product pre order page while saving the product for pre order

    public static function isProductInActiveWarehousePreOrderList(
        $warehouseCode,
        $whPreOrderListingCode,
        $productCode,
        $productVariantCode,
        $select = '*'
    ){
        return  WarehousePreOrderListing::where('warehouse_code',$warehouseCode)
            ->where('warehouse_preorder_listings.warehouse_preorder_listing_code',$whPreOrderListingCode)
            ->where('warehouse_preorder_listings.is_active',1)
            ->join('warehouse_preorder_products',
                'warehouse_preorder_products.warehouse_preorder_listing_code',
                '=',
                'warehouse_preorder_listings.warehouse_preorder_listing_code'
            )
//            ->join('products_master',
//                'products_master.product_code',
//                '=',
//                'warehouse_preorder_products.product_code'
//            )
            ->where('warehouse_preorder_products.is_active',1)
            ->where('warehouse_preorder_products.product_code',$productCode)
            ->where('warehouse_preorder_products.product_variant_code',$productVariantCode)
            //->select('products_master.is_taxable','products_master.product_code')
            ->where('start_time','<=',Carbon::now('Asia/Kathmandu'))
            ->where('end_time','>=',Carbon::now('Asia/Kathmandu'))
//            ->whereHas('warehousePreOrderProducts'
//                ,function ($query) use($productCode,$productVariantCode){
//                    $query->where('is_active',1)
//                        ->where('product_code',$productCode)
//                        ->where('product_variant_code',$productVariantCode);
//                })
            ->select($select)
            ->first();
    }

    //usage in single product pre order page
    public static function getStorePreOrderInWhPreOrderListingCode(
        $whPreOrderListingCode,
        $storeCode
    ){
        return StorePreOrder::where('warehouse_preorder_listing_code',$whPreOrderListingCode)
            ->where('store_code',$storeCode)
            ->first();
    }

    public static function getStorePreOrderInPendingStateInWhPreOrderListingCode(
        $whPreOrderListingCode,
        $storeCode
    ){
        return StorePreOrder::where('warehouse_preorder_listing_code',$whPreOrderListingCode)
            ->where('store_code',$storeCode)
            ->where('status','pending')
            ->first();
    }



    public static function filterStorePreOrder($storeCode,$filterParameters,$paginateBy)
    {
        //dd($filterParameters);
        $now_time = Carbon::now('Asia/Kathmandu')->toDateTimeString();
        //dd($now_time);
        $priceCondition=isset($filterParameters['price_condition']) && in_array($filterParameters['price_condition'],['>','<', '>=','<=','='])? true:false;

        $vat_percent = 1+ (StorePreOrder::VAT_PERCENTAGE_VALUE/100);
        $storePreOrder =  StorePreOrder::select(
            'store_preorder.store_preorder_code',
            'store_preorder.warehouse_preorder_listing_code',
            'store_preorder.store_code',
            'store_preorder.payment_status',
            'store_preorder.created_at',
            'store_preorder.status',
            'warehouse_preorder_listings.warehouse_code',
            'warehouse_preorder_listings.start_time',
            'warehouse_preorder_listings.end_time',
            'warehouse_preorder_listings.finalization_time',
            'warehouse_preorder_listings.is_active',
            'warehouses.warehouse_name'
        )
            ->withCount('storePreOrderDetails')
            ->leftJoin('warehouse_preorder_listings',
                'warehouse_preorder_listings.warehouse_preorder_listing_code',
                '=',
                'store_preorder.warehouse_preorder_listing_code'
            )
            ->leftJoin('warehouses','warehouses.warehouse_code',
                '=',
                'warehouse_preorder_listings.warehouse_code')
            ->leftJoin('store_preorder_details as t2',function ($join){
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
            ->addSelect(DB::raw('
                             ROUND(SUM(
                                   Case t2.is_taxable when "1" Then
                                        (
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
                                    ) * t2.quantity + (
                                               0.13 * (
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
                                               )
                                        )
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
                           '))
            ->addSelect(DB::Raw('
                                       (
                                         CASE  WHEN warehouse_preorder_listings.end_time < "'.$now_time.'"
                                         THEN 1
                                         ELSE 0
                                         END
                                       ) as pre_order_elapsed
                                       '))
            ->when(isset($filterParameters['store_preorder_code']),function ($query) use($filterParameters){
                $query->where('store_preorder.store_preorder_code','like','%'.$filterParameters['store_preorder_code'] . '%');
            })
            ->when(isset($filterParameters['start_time']) && isset($filterParameters['end_time']),
                function($query) use($filterParameters){
                    $query->whereDate('warehouse_preorder_listings.start_time','>=',date('y-m-d',strtotime($filterParameters['start_time'])))
                        ->whereDate('warehouse_preorder_listings.end_time','<=',date('y-m-d',strtotime($filterParameters['end_time'])));
                })
            ->when(isset($filterParameters['payment_status']),function($query) use ($filterParameters){
                $query->where('store_preorder.payment_status',$filterParameters['payment_status']);
            })

            ->when(isset($filterParameters['status']),function($query) use ($filterParameters){
                $query->where('store_preorder.status',$filterParameters['status']);
            })
            ->groupBy('store_preorder.store_preorder_code')
            ->when($priceCondition && isset($filterParameters['total_price']),function ($query) use($filterParameters){
                $query->having('total_price',$filterParameters['price_condition'],$filterParameters['total_price']);
            })
            ->where('store_code',$storeCode)
            ->orderBy('warehouse_preorder_listings.end_time','desc')
            ->paginate($paginateBy);

        return $storePreOrder;
    }

    public static function newfilterStorePreOrder($storeCode, $filterParameters, $paginateBy)
    {
        //dd($filterParameters);
        $now_time = Carbon::now('Asia/Kathmandu')->toDateTimeString();
        //dd($now_time);
        $priceCondition = isset($filterParameters['price_condition']) && in_array($filterParameters['price_condition'], ['>', '<', '>=', '<=', '=']) ? true : false;

       // $vat_percent = 1 + (StorePreOrder::VAT_PERCENTAGE_VALUE / 100);
        $storePreOrder = StorePreOrderView::select(
            'store_pre_orders_view.store_preorder_code',
            'store_pre_orders_view.warehouse_preorder_listing_code',
            'store_pre_orders_view.store_code',
            'store_pre_orders_view.payment_status',
            'store_pre_orders_view.status',
            'store_pre_orders_view.created_at',
            'warehouse_preorder_listings.warehouse_code',
            'warehouse_preorder_listings.start_time',
            'warehouse_preorder_listings.end_time',
            'warehouse_preorder_listings.finalization_time',
            'warehouse_preorder_listings.is_active',
            'warehouses.warehouse_name',
            'store_pre_orders_view.total_price'
        )
            ->join('warehouse_preorder_listings',
                'warehouse_preorder_listings.warehouse_preorder_listing_code',
                '=',
                'store_pre_orders_view.warehouse_preorder_listing_code'
            )
            ->join('warehouses', 'warehouses.warehouse_code',
                '=',
                'warehouse_preorder_listings.warehouse_code')
            ->join('store_preorder_details', function ($join) {
                $join->on('store_preorder_details.store_preorder_code', '=', 'store_pre_orders_view.store_preorder_code')
                    ->whereNULL('store_preorder_details.deleted_at');
            })
//            ->addSelect(DB::Raw('
//                                       (
//                                         CASE  WHEN warehouse_preorder_listings.end_time < "' . $now_time . '"
//                                         THEN 1
//                                         ELSE 0
//                                         END
//                                       ) as pre_order_elapsed
//                                       '))

            ->addSelect(DB::raw('COUNT(store_preorder_details.id) as store_pre_order_details_count'))
            ->when(isset($filterParameters['store_preorder_code']),function ($query) use($filterParameters){
                $query->where('store_pre_orders_view.store_preorder_code','like','%'.$filterParameters['store_preorder_code'] . '%');
            })
            ->when(isset($filterParameters['start_time']) && isset($filterParameters['end_time']),
                function($query) use($filterParameters){
                    $query->whereDate('warehouse_preorder_listings.start_time','>=',date('y-m-d',strtotime($filterParameters['start_time'])))
                        ->whereDate('warehouse_preorder_listings.end_time','<=',date('y-m-d',strtotime($filterParameters['end_time'])));
                })
            ->when(isset($filterParameters['payment_status']),function($query) use ($filterParameters){
                $query->where('store_pre_orders_view.payment_status',$filterParameters['payment_status']);
            })
            ->groupBy('store_pre_orders_view.store_preorder_code')
            ->when($priceCondition && isset($filterParameters['total_price']),function ($query) use($filterParameters){
                $query->having('total_price',$filterParameters['price_condition'],$filterParameters['total_price']);
            })
            ->where('store_code',$storeCode)
            //->where('warehouse_preorder_listings.status_type','!=','cancelled')
            ->orderBy('warehouse_preorder_listings.end_time','desc')
            ->paginate($paginateBy);

        return $storePreOrder;
    }



    public static function getProductInWarehousePreOrderList(
        $warehousePreOrderListingCode,
        $productSlug,
        $with=[]
    ){

        $warehouseProduct= ProductMaster::with($with)
            ->whereHas('warehousePreOrderProducts',
                function ($query) use ($warehousePreOrderListingCode){
                    $query->where('warehouse_preorder_listing_code',$warehousePreOrderListingCode)
                        ->active();
                })
            ->where('slug',$productSlug)
            ->first();

        if(!$warehouseProduct){
            throw new \Exception('No product found in the pre-order listing',404);
        }

        return $warehouseProduct;
    }

    public static function getWarehouseCode($warehouseListingCode)
    {
        $warehouseDetail = WarehousePreOrderListing::where('warehouse_preorder_listing_code',$warehouseListingCode)->latest()->first();
        $warehouseCode = $warehouseDetail['warehouse_code'];
        return $warehouseCode;
    }

    public static function getWarehouseCodeByStorePreOrderCode($storePreOrderCode)
    {
        $warehouseCode = StorePreOrder::where('store_preorder_code',$storePreOrderCode)
            ->join('warehouse_preorder_listings',
                'warehouse_preorder_listings.warehouse_preorder_listing_code',
                '=', 'store_preorder.warehouse_preorder_listing_code')
            ->select('warehouse_preorder_listings.warehouse_code')->firstOrFail();
        return $warehouseCode->warehouse_code;
    }
    public static function getTotalAmountOfStorePreOrder($storePreOrderCode)
    {
        $storePreOrder = StorePreOrderView::where('store_preorder_code', $storePreOrderCode)->firstOrFail();
        return $storePreOrder->total_price;
    }


    public static function getAmountGroupingsOfStorePreOrders($storeCode)
    {
        $amountByStatus = StorePreOrderView::where('store_code', $storeCode)
                           ->select('status')
                           ->addSelect(DB::raw('sum(total_price) as amount'))
                           ->groupBy('status')
                           ->get();


        $keyed = $amountByStatus->mapWithKeys(function ($item, $key) {
            return [
                'total_'.$item['status'].'_amount' => $item['amount']
            ];
        });

       return  $keyed->all();
    }

    public static function getStoreParticipantsInPreOrder($filterParameters, $paginateBy = 20, $with = [])
    {
        $connectedWHStores = StoreWarehouseHelper::getStoresConnectedWithWarehouse(getAuthWarehouseCode());
        $stores = Store::with($with)
            ->join('store_pre_orders_view', function ($join) {
                $join->on('store_pre_orders_view.store_code', '=', 'stores_detail.store_code');
            })->select(
                'stores_detail.store_code',
                'stores_detail.store_name',
                'store_pre_orders_view.total_price'
            )
            ->when(isset($filterParameters['store_name']), function ($query) use ($filterParameters) {
                $query->where('stores_detail.store_name', 'like', '%' . $filterParameters['store_name'] . '%');
            })
            //  ->addSelect(DB::raw('sum(store_pre_orders.total_price) as finalized_price'))
            ->addSelect(DB::raw('SUM(case store_pre_orders_view.status when "finalized" then store_pre_orders_view.total_price ELSE 0 end) as finalized_total_price'))
            ->addSelect(DB::raw('SUM(case store_pre_orders_view.status when "dispatched" then store_pre_orders_view.total_price  ELSE 0  end) as dispatched_total_price'))
            ->addSelect(DB::raw('SUM(case store_pre_orders_view.status when "processing" then store_pre_orders_view.total_price  ELSE 0  end) as processing_total_price'))
            ->addSelect(DB::raw('SUM(case store_pre_orders_view.status when "pending" then store_pre_orders_view.total_price  ELSE 0 end) as pending_total_price'))
            ->addSelect(DB::raw('SUM(case store_pre_orders_view.status when "cancelled" then store_pre_orders_view.total_price  ELSE 0 end) as cancelled_total_price'))
            ->addSelect(DB::raw('COUNT(store_pre_orders_view.store_preorder_code) as total_preorders'))
            ->whereIn('stores_detail.store_code',$connectedWHStores)
            ->groupBy('store_pre_orders_view.store_code')
            ->orderBy('stores_detail.store_name', 'ASC')
            ->paginate($paginateBy);
        return $stores;
    }

    public static function getPreOrdersMadeByStoreCode(
        $storeCode, $filterParameters, $paginateBy = 20, $with = [])
    {
        $preOrders = StorePreOrderView::with($with)
            ->select(
                'store_pre_orders_view.store_preorder_code',
                'store_pre_orders_view.warehouse_preorder_listing_code',
                'store_pre_orders_view.total_price',
                'store_pre_orders_view.status',
                'store_pre_orders_view.early_finalized',
                'store_pre_orders_view.early_cancelled',
                'store_pre_orders_view.payment_status',
                'store_pre_orders_view.created_at',
                'warehouse_preorder_listings.start_time',
                'warehouse_preorder_listings.end_time',
                'warehouse_preorder_listings.finalization_time',
                'warehouse_preorder_listings.pre_order_name'
            )
            ->join('warehouse_preorder_listings', function ($join) {
                $join->on('store_pre_orders_view.warehouse_preorder_listing_code',
                    '=', 'warehouse_preorder_listings.warehouse_preorder_listing_code'
                );
            })->when(isset($filterParameters['pre_order_name']), function ($query) use ($filterParameters) {
                $query->where('warehouse_preorder_listings.pre_order_name',
                    'like', '%' . $filterParameters['pre_order_name'] . '%');
            })->when(isset($filterParameters['statuses']), function ($query) use ($filterParameters) {
                $query->whereIn('store_pre_orders_view.status', $filterParameters['statuses']);
            })->when(isset($filterParameters['payment_status']), function ($query) use ($filterParameters) {
                $query->where('store_pre_orders_view.payment_status', $filterParameters['payment_status']);
            })->when(isset($filterParameters['start_time']), function ($query) use ($filterParameters) {
                $query->whereDate('warehouse_preorder_listings.start_time', '>=', date('y-m-d', strtotime($filterParameters['start_time'])));
            })->when(isset($filterParameters['end_time']), function ($query) use ($filterParameters) {
                $query->whereDate('warehouse_preorder_listings.end_time', '<=', date('y-m-d', strtotime($filterParameters['end_time'])));
            })->where('store_pre_orders_view.store_code', $storeCode)
            ->latest('store_pre_orders_view.created_at')
            ->paginate($paginateBy);

        return $preOrders;
    }


    public static function isStorePreOrderFinalizableByReason($storePreOrderCode,$reason)
    {


        if ($reason == 'non_deleted_preorder_details') {
            $storePreOrder = StorePreOrder::join('store_preorder_details', function ($join) {
                $join->on('store_preorder_details.store_preorder_code', '=', 'store_preorder.store_preorder_code')
                    ->whereNull('store_preorder_details.deleted_at');
            })
                ->where('store_preorder.store_preorder_code', $storePreOrderCode)
                ->get();

        }

        if ($reason == 'active_preorder_products') {
            $storePreOrder = StorePreOrder::select('store_preorder.store_preorder_code')
                ->join('store_preorder_details', function ($join) {
                    $join->on('store_preorder_details.store_preorder_code', '=', 'store_preorder.store_preorder_code');
                })
                ->join('warehouse_preorder_products', function ($join) {
                    $join->on('warehouse_preorder_products.warehouse_preorder_product_code', '=', 'store_preorder_details.warehouse_preorder_product_code')
                        ->where('warehouse_preorder_products.is_active', 1);
                })
                ->where('store_preorder.store_preorder_code', $storePreOrderCode)
                ->get();
        }

        return count($storePreOrder) ? true : false;
    }

//    done By Govinda

    public static function getPreOrderTargetsOfDetail($storePreOrderCode)
    {

        $authUserCode = getAuthStoreCode();
        $authUserStoreType= Store::where('store_code',$authUserCode)->first();
        $storePreOrderTotal=self::getTotalPreOrderPrice();
        $storePreOrderTagetValues= StorePreOrderView::
        select('store_pre_orders_view.total_price')
            ->join('preorder_target',function($join) use($storePreOrderCode){
                $join->on('preorder_target.warehouse_preorder_listing_code','=','store_pre_orders_view.warehouse_preorder_listing_code')
                    ->where('store_pre_orders_view.store_preorder_code',$storePreOrderCode);
            })
            ->where('preorder_target.store_type_code',function ($query) use ($authUserCode){
                $query->select('store_type_code')
                    ->from('stores_detail')
                    ->where('store_code',$authUserCode);
            })
            ->where('preorder_target.store_type_code',$authUserStoreType->store_type_code)
            ->addSelect(DB::raw('SUM(CASE preorder_target.target_type WHEN "group"  THEN
              preorder_target.target_value ELSE 0 END) as total_group_target
            '))
            ->addSelect(DB::raw('SUM(CASE preorder_target.target_type WHEN "individual"  THEN
              preorder_target.target_value ELSE 0 END) as total_individual_target
            '))
            ->first();
        $storePreOrderTagetValues['total_group_order']=$storePreOrderTotal->total_group_order;
        return $storePreOrderTagetValues;
    }
    public static function getTargets()
    {
        //dd($filterParameters);
        $authUserCode = getAuthStoreCode();
        $authUserStoreType= Store::where('store_code',$authUserCode)->first();
        $storeOrdersTotal=self::getTotalPreOrderPrice();
//        dd($storeOrdersTotal);
        $preOrderSubJoin = WarehousePreOrderTarget::
        select('store_type_code','store_pre_orders_view.warehouse_preorder_listing_code','preorder_target_code')
            ->join('store_pre_orders_view',
                'store_pre_orders_view.warehouse_preorder_listing_code','=','preorder_target.warehouse_preorder_listing_code')
            ->where('preorder_target.store_type_code',function ($query) use ($authUserCode){
                $query->select('store_type_code')
                    ->from('stores_detail')
                    ->where('store_code',$authUserCode);
            })
//            ->select('store_pre_orders_view.warehouse_preorder_listing_code', DB::raw('SUM(store_pre_orders_view.total_price) as store_type_total_price'))
            ->where('preorder_target.store_type_code',$authUserStoreType->store_type_code)
            ->addSelect(DB::raw('CASE preorder_target.target_type WHEN "group"  THEN
              preorder_target.target_value ELSE 0 END as total_group_target
            '))
            ->addSelect(DB::raw('CASE preorder_target.target_type WHEN "individual"  THEN
              preorder_target.target_value ELSE 0 END as total_individual_target
            '));
//        dd($preOrderSubJoin);
//            ->groupBy('store_type_code','target_type')

            $preOrderTargets=StorePreOrderView::joinSub($preOrderSubJoin, 'preorder_sub', function ($join) {
                $join->on('store_pre_orders_view.warehouse_preorder_listing_code', '=', 'preorder_sub.warehouse_preorder_listing_code');
            })
            ->join('warehouse_preorder_listings',
                'warehouse_preorder_listings.warehouse_preorder_listing_code',
                '=',
                'store_pre_orders_view.warehouse_preorder_listing_code'
            )
            ->select('store_pre_orders_view.warehouse_preorder_listing_code',
                'store_pre_orders_view.total_price')
            ->selectRaw('sum(preorder_sub.total_group_target) as total_group_target')
            ->selectRaw('sum(preorder_sub.total_individual_target) as total_individual_target')
            ->groupBy('preorder_sub.store_type_code','store_pre_orders_view.warehouse_preorder_listing_code')
            ->orderBy('warehouse_preorder_listings.end_time','desc')
            ->get();

        foreach($preOrderTargets as $key=>$data)
        {
            $preOrderTargets[$key]['total_group_order']=$storeOrdersTotal->total_group_order;
        }
//        dd($preOrderTargets);
        return $preOrderTargets;
    }
    public static function getTotalPreOrderPrice()
    {
        return DB::table('store_pre_orders_view')
            ->Join('stores_detail','stores_detail.store_code','=','store_pre_orders_view.store_code')
            ->Join('warehouse_preorder_listings',function($join){
                $join->on('warehouse_preorder_listings.warehouse_preorder_listing_code','=','store_pre_orders_view.warehouse_preorder_listing_code');
            })
            ->select('stores_detail.store_code', DB::raw('SUM(store_pre_orders_view.total_price) as total_group_order'))
            ->groupBy('stores_detail.store_type_code')
            ->first();

    }

    public static function filterPreOrderWiseStorePreOrderforAdmin($warehousePreOrderListingCode,$filterParameters,$paginateBy=10){

        $priceCondition = isset($filterParameters['price_condition']) && in_array($filterParameters['price_condition'], ['>', '<', '>=', '<=', '=']) ? true : false;

        $storePreOrder = StorePreOrderView::select(
                'store_pre_orders_view.store_preorder_code',
                'store_pre_orders_view.warehouse_preorder_listing_code',
                'store_pre_orders_view.store_code',
                'store_pre_orders_view.payment_status',
                'store_pre_orders_view.status',
                'store_pre_orders_view.created_at',
                'warehouse_preorder_listings.warehouse_code',
                'warehouse_preorder_listings.start_time',
                'warehouse_preorder_listings.end_time',
                'warehouse_preorder_listings.finalization_time',
                'warehouse_preorder_listings.is_active',
                'store_pre_orders_view.total_price'
            )
            ->join('warehouse_preorder_listings',
                'warehouse_preorder_listings.warehouse_preorder_listing_code',
                '=',
                'store_pre_orders_view.warehouse_preorder_listing_code'
            )
            ->join('store_preorder_details', function ($join) {
                $join->on('store_preorder_details.store_preorder_code', '=', 'store_pre_orders_view.store_preorder_code')
                    ->whereNULL('store_preorder_details.deleted_at');
            })
            ->where('store_pre_orders_view.warehouse_preorder_listing_code',$warehousePreOrderListingCode)
            ->when(isset($filterParameters['store_preorder_code']),function ($query) use($filterParameters){
                $query->where('store_pre_orders_view.store_preorder_code','like','%'.$filterParameters['store_preorder_code'] . '%');
            })
            ->when(isset($filterParameters['store_name']),function ($query) use($filterParameters){
                $query->whereHas('store',function ($query) use ($filterParameters){
                   $query->where('store_name','like','%'.$filterParameters['store_name'] . '%');
                });
            })
            ->when(isset($filterParameters['start_time']) && isset($filterParameters['end_time']),
                function($query) use($filterParameters){
                    $query->whereDate('store_pre_orders_view.created_at','>=',date('y-m-d',strtotime($filterParameters['start_time'])))
                        ->whereDate('store_pre_orders_view.created_at','<=',date('y-m-d',strtotime($filterParameters['end_time'])));
            })
            ->when(isset($filterParameters['payment_status']),function($query) use ($filterParameters){
                $query->where('store_pre_orders_view.payment_status',$filterParameters['payment_status']);
            })
            ->when(isset($filterParameters['status']),function($query) use ($filterParameters){
                $query->where('store_pre_orders_view.status',$filterParameters['status']);
            })
            ->groupBy('store_pre_orders_view.store_preorder_code')
            ->when($priceCondition && isset($filterParameters['total_price']),function ($query) use($filterParameters){
                $query->having('total_price',$filterParameters['price_condition'],$filterParameters['total_price']);
            })
            ->orderBy('warehouse_preorder_listings.end_time','desc')
            ->paginate($paginateBy);

        return $storePreOrder;
    }
}
