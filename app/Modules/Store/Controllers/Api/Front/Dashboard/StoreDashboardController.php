<?php

namespace App\Modules\Store\Controllers\Api\Front\Dashboard;

 use App\Http\Controllers\Controller;
 use App\Modules\Store\Helpers\StoreAccessBarrierHelper;
 use App\Modules\Store\Models\StoreOrder;
use App\Modules\Store\Resources\StoreOrder\StoreOrderListResource;
use Illuminate\Support\Facades\DB;

class StoreDashboardController extends Controller
{


    public function getDashboardStats()
    {

        $authStoreCode = getAuthStoreCode();

        $orderDeliveryStatuses = array_values(StoreOrder::DELIVERY_STATUSES);


        //store orders grouped by delivery_status

        $storeOrdersByDeliveryStatus  = StoreOrder::where('store_code',$authStoreCode)
                                                ->select('delivery_status', DB::raw('count("*") as count'))
                                                ->groupBy('delivery_status')->get();

        $groupedStoreOrderByDeliveryStatus = [];
        $totalOrders = 0;

        foreach ($storeOrdersByDeliveryStatus as $storeOrder){
            $totalOrders += $storeOrder->count;
            $groupedStoreOrderByDeliveryStatus[$storeOrder->delivery_status] = $storeOrder->count;
        }
        $groupedStoreOrderByDeliveryStatus['total_orders'] = $totalOrders;

       foreach ($orderDeliveryStatuses as $orderDeliveryStatus){
            if(!in_array($orderDeliveryStatus,array_keys($groupedStoreOrderByDeliveryStatus))){
                $groupedStoreOrderByDeliveryStatus[$orderDeliveryStatus] = 0;
            }
        }

        $latestStoreOrders = StoreOrder::where('store_code',$authStoreCode)->latest()->limit(10)->get();



        // Store Orders Grouped By PaymentStatus
        $paymentPendingStoreOrdersCount = StoreOrder::where('store_code',$authStoreCode)
            ->whereHas('offlinePayments', function ($query)  {
                $query->whereRaw('id = (select max(id) from store_order_offline_payments where store_order_offline_payments.store_order_code = store_orders.store_order_code)')
                    ->where('payment_status','pending');
            })->count();


        // store barrier conditions
        $isKycVerified = StoreAccessBarrierHelper::isKycVerifiedForStore($authStoreCode);
       // $isInitialRegistrationVerified = StoreAccessBarrierHelper::isInitialRegistrationVerifiedForStore($authStoreCode);
       $isInitialRegistrationVerified = true;
       $overAllVerified = $isKycVerified && $isInitialRegistrationVerified ? true: false;
        $storeBarrierConditions =[
            'verified' => $overAllVerified,
            'kyc_verified' =>[
                'status' => $isKycVerified,
                'message' => !$isKycVerified ? "Please get your firm kyc and sanchalak kyc verified" : "Your Kycs are verifed"
            ] ,
            'initial_registration_verified'=>[
                'status' => $isInitialRegistrationVerified,
                'message' => !$isInitialRegistrationVerified ? "Please get initial registration payment verified" : "Your  initial registration payment is verifed"
            ] ,
        ];


        $results = [
            'store_barrier_conditions' => $storeBarrierConditions
        ];

        $results['latestOrders'] = [];
        $results['orders_count'] = [];


        if($overAllVerified){

            foreach ($groupedStoreOrderByDeliveryStatus as $statusName => $storeOrderByDeliveryStatusCount){
                $results['orders_count']['delivery_status'][$statusName] = $storeOrderByDeliveryStatusCount;
            }

            $results['orders_count']['payment_status']['pending'] = $paymentPendingStoreOrdersCount;

            $results['latestOrders'] = StoreOrderListResource::collection($latestStoreOrders);

        }



        return $results;

//        return [
//            //'products_count' => $verifiedProductsCount,
//            'orders_count' => $overAllVerified ?  $groupedStoreOrderByDeliveryStatus['total'] : 'N/A',
//            'latestOrders' => $overAllVerified ? StoreOrderListResource::collection($latestStoreOrders): [],
//            'store_barrier_conditions' => $storeBarrierConditions
//        ];
    }
}
