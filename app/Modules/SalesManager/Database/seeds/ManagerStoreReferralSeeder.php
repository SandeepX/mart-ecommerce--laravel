<?php
namespace  App\Modules\SalesManager\Database\seeds;

use App\Modules\SalesManager\Models\ManagerStoreReferral;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Modules\Store\Models\Store;
use App\Modules\SalesManager\Models\Manager;
class ManagerStoreReferralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            DB::beginTransaction();

            $stores = Store::where('referred_by','!=','U00000001')
                             ->get();

            foreach ($stores as $store){
                $manager = Manager::where('user_code',$store->referred_by)->first();
                $managerStoreReferralsData = [];
                $managerStoreReferralsData['manager_code'] = $manager->manager_code;
                $managerStoreReferralsData['referred_store_code'] = $store->store_code;
                $managerStoreReferralsData['referred_incentive_amount'] = $store->referred_incentive_amount;
                $managerStoreReferralsData['referred_incentive_amount_meta'] = NULL;
                if($store->referred_incentive_amount > 0){
                    $orders = $store->orders->where('delivery_status','dispatched')->first();
                    $preOrders = $store->preOrders->where('status','dispatched')->first();
                    $incentiveMetaData = [];
                    if(isset($orders->updated_at) &&  isset($preOrders->updated_at)){
                        if($orders->updated_at < $preOrders->updated_at){
                            $incentiveMetaData['source'] = 'normal_order';
                            $incentiveMetaData['source_code'] = $orders->store_order_code;
                            $incentiveMetaData['incentive_received_at'] = $orders->updated_at;
                        }else{
                            $incentiveMetaData['source'] = 'preorder';
                            $incentiveMetaData['source_code'] = $preOrders->store_preorder_code;
                            $incentiveMetaData['incentive_received_at'] = $preOrders->updated_at;
                        }
                    }elseif(isset($orders->updated_at)){
                        $incentiveMetaData['source'] = 'normal_order';
                        $incentiveMetaData['source_code'] = $orders->store_order_code;
                        $incentiveMetaData['incentive_received_at'] = $orders->updated_at;
                    }elseif(isset($preOrders->updated_at)){
                        $incentiveMetaData['source'] = 'preorder';
                        $incentiveMetaData['source_code'] = $preOrders->store_preorder_code;
                        $incentiveMetaData['incentive_received_at'] = $preOrders->updated_at;
                    }
                    $managerStoreReferralsData['referred_incentive_amount_meta'] = json_encode($incentiveMetaData);
                }

                $managerStoreReferralsData['created_by'] = 'U00000001';
                $managerStoreReferralsData['updated_by'] = 'U00000001';
                ManagerStoreReferral::create($managerStoreReferralsData);
                echo " Store Referral added for ".$store->store_name." ".$store->store_code." \n";
            }
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            echo $exception->getMessage();
        }
    }
}
