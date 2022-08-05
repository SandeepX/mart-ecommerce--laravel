<?php

namespace App\Modules\Store\Repositories;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\SalesManager\Models\Manager;
use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\StoreApi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Exception;

class StoreRepository extends RepositoryAbstract
{

    use ImageService;
    public function getAllStores($with=[])
    {
        return Store::with($with)->latest()->get();
    }

    public function getAllStoresByActiveStatus($activeStatus,$with=[])
    {
        $stores= Store::with($with)->select($this->select);

        if ($activeStatus){
            $stores = $stores->active();
        }
        else{
            $stores= $stores->notActive();
        }

        return $stores->latest()->get();
    }

    public function getStoresHavingWarehouses($with)
    {
        return Store::whereHas('warehouses')->where('status','approved')->with($with)->latest()->get();
    }


    public function findStoreByCode($StoreCode)
    {
        return Store::where('store_code', $StoreCode)->first();
    }

    public function getStoresByCode(array $storesCode)
    {
        return Store::with($this->with)->select($this->select)
            ->orderBy($this->orderByColumn,$this->orderDirection)
            ->whereIn('store_code',$storesCode)->get();
    }

    public function findOrFailStoreByCode($StoreCode,$with=[],$select='*')
    {
        return Store::with($with)->select($select)->where('store_code', $StoreCode)->firstOrFail();
    }

    public function findStoreById($StoreId)
    {
        return Store::where('id', $StoreId)->first();
    }

    public function findOrFailStoreById($StoreId)
    {
        if ($Store = $this->findStoreById($StoreId)) {
            return $Store;
        }

        throw new ModelNotFoundException('No Such Store Found !');
    }

    public function findStoreBySlug($StoreSlug)
    {
        return Store::where('slug', $StoreSlug)->first();
    }

    public function findOrFailStoreBySlug($StoreSlug)
    {
        if ($Store = $this->findStoreBySlug($StoreSlug)) {
            return $Store;
        }

        throw new ModelNotFoundException('No Such Store Found !');
    }

    public function findStoreDetailForSupportAdmin($filterParameters)
    {
       // $filterParameters['store_name'] = strtolower($filterParameters['store_name']);
//        $store = Store::where(function($query) use ($filterParameterData){
//                            if($filterParameterData['store_code']) {
//                                $query->where('store_code', $filterParameterData['store_code']);
//                            }
//                            if($filterParameterData['store_name']) {
//                               $query->where('store_name', $filterParameterData['store_name']);
//                            }
//                            if($filterParameterData['store_phone']) {
//                               $query->where('store_contact_mobile', $filterParameterData['store_phone']);
//                            }
//                            if($filterParameterData['store_email']) {
//                               $query->where('store_email', $filterParameterData['store_email']);
//                            }
//                        })
//                       ->where('is_active',1)
////                       ->where('status','approved')
//                       ->get(['store_code','store_name']);
//       return $store;

        $storeOrders = DB::table('store_orders')
            ->select('store_code',DB::raw('COUNT(store_order_code) as total_orders'))
            ->groupBy('store_code');

        $storePreOrders = DB::table('store_pre_orders_view')
            ->select('store_code',DB::raw('COUNT(store_preorder_code) as total_preorders'))
            ->groupBy('store_code');

        $individualKyc = DB::table('individual_kyc_master')
            ->select('store_code',DB::raw('COUNT(kyc_code) as total_individual_kyc'))
            ->groupBy('store_code');

        $firmKyc = DB::table('firm_kyc_master')
            ->select('store_code',DB::raw('COUNT(kyc_code) as total_firm_kyc'))
            ->groupBy('store_code');

        $withdrawRequests = DB::table('store_balance_withdraw_request')
            ->select('store_code',DB::raw('COUNT(store_balance_withdraw_request_code) as total_withdraw_request'))
            ->groupBy('store_code');

        $storeBalancePayments = DB::table('store_miscellaneous_payments')
            ->select('store_code',DB::raw('COUNT(store_misc_payment_code) as total_misc_payment'))
            ->groupBy('store_code');
        $statements = DB::table('wallets')
            ->join('wallet_transaction','wallet_transaction.wallet_code','=','wallets.wallet_code')
            ->select('wallets.wallet_holder_code',DB::raw('COUNT(wallet_transaction.wallet_transaction_code) as total_statement'))
            ->groupBy('wallet_transaction.wallet_code');

        $investmentPlans = DB::table('investment_plan_subscriptions')
            ->select('investment_holder_id',
                DB::raw('COUNT(ip_subscription_code) as total_investment'))
            ->where('investment_holder_type','=','store')
            ->groupBy('investment_holder_id');

        $store = DB::table('stores_detail')
            ->select(
                'stores_detail.store_code',
                'stores_detail.store_name',
                'stores_detail.store_email',
                'stores_detail.store_contact_phone',
                'stores_detail.store_contact_mobile',
                'store_orders.total_orders',
                'store_pre_orders.total_preorders',
                'individual_kyc.total_individual_kyc',
                'firm_kyc.total_firm_kyc',
                'withdraw_request.total_withdraw_request',
                'store_balance_payments.total_misc_payment',
                'statements.total_statement',
                'investment_plans.total_investment'

            )

            ->leftJoinSub($storeOrders,'store_orders',function($join){
                $join->on('stores_detail.store_code','=','store_orders.store_code');
            })
            ->leftJoinSub($storePreOrders,'store_pre_orders',function($join){
                $join->on('stores_detail.store_code','=','store_pre_orders.store_code');
            })
            ->leftJoinSub($individualKyc,'individual_kyc',function($join){
                $join->on('stores_detail.store_code','=','individual_kyc.store_code');
            })
            ->leftJoinSub($firmKyc,'firm_kyc',function($join){
                $join->on('stores_detail.store_code','=','firm_kyc.store_code');
            })
            ->leftJoinSub($withdrawRequests,'withdraw_request',function($join){
                $join->on('stores_detail.store_code','=','withdraw_request.store_code');
            })
            ->leftJoinSub($storeBalancePayments,'store_balance_payments',function($join){
                $join->on('stores_detail.store_code','=','store_balance_payments.store_code');
            })
            ->leftJoinSub($statements,'statements',function($join){
                $join->on('stores_detail.store_code','=','statements.wallet_holder_code');
            })
            ->leftJoinSub($investmentPlans,'investment_plans',function($join){
                $join->on('stores_detail.store_code','=','investment_plans.investment_holder_id');
            })

            ->when(isset($filterParameters['store_name']), function ($query) use ($filterParameters) {
                $query->where(strtolower('stores_detail.store_name'),strtolower($filterParameters['store_name']));
            })->when(isset($filterParameters['store_email']), function ($query) use ($filterParameters) {
                $query->where('stores_detail.store_email',$filterParameters['store_email']);
            })->when(isset($filterParameters['store_code']), function ($query) use ($filterParameters) {
                $query->where('stores_detail.store_code', $filterParameters['store_code']);
            })
            ->when(isset($filterParameters['store_phone']), function ($query) use ($filterParameters) {
                $query->where(function ($query) use ($filterParameters) {
                    $query->where('stores_detail.store_contact_mobile',$filterParameters['store_phone'])
                        ->orWhere('stores_detail.store_contact_phone',$filterParameters['store_phone']);
                });
            })
            ->where('stores_detail.is_active',1)
            ->whereNull('stores_detail.deleted_at')
            ->where('stores_detail.status','approved')
            ->first();
        return $store;
    }

    public function create($validatedStore)
    {

        try {
            //handle Image
            $validatedStore['store_logo'] = $this->storeImageInServer($validatedStore['store_logo'], Store::IMAGE_PATH);
            $validatedStore['slug'] = makeSlugWithHash($validatedStore['store_name']);
            $store = Store::create($validatedStore)->fresh();
            $store->store_full_location = $store->getFullLocationPath();
            $store->save();
            return $store;
        } catch (Exception $e) {
            $this->deleteImageFromServer(Store::IMAGE_PATH, $validatedStore['store_logo']);
            throw $e;
        }
    }

    public function createFromApi($validatedStore)
    {

        try {
           // dd($validatedStore);
            //handle Image
            if(isset($validatedStore['store_logo'])){
                $validatedStore['store_logo'] = $this->storeImageInServer($validatedStore['store_logo'], Store::IMAGE_PATH);
            }
            $validatedStore['slug'] = makeSlugWithHash($validatedStore['store_name']);
           // dd($validatedStore);
            $store = Store::create($validatedStore)->fresh();
            $store->store_full_location = $store->getFullLocationPath();
            $store->save();
            return $store;

        } catch (Exception $e) {
            if(isset($validatedStore['store_logo'])) {
                $this->deleteImageFromServer(Store::IMAGE_PATH, $validatedStore['store_logo']);
            }
            throw $e;
        }
    }

    public function update($validatedStore, $store)
    {
        try {
            $validatedStore['slug'] = Str::slug($validatedStore['store_name']);

            if(isset($validatedStore['store_logo'])){
                $this->deleteImageFromServer(Store::IMAGE_PATH, $store->store_logo);
                $validatedStore['store_logo'] = $this->storeImageInServer($validatedStore['store_logo'], Store::IMAGE_PATH);
            }

            $store->update($validatedStore);
            return $store->fresh();
        } catch (Exception $e) {
            $this->deleteImageFromServer(Store::IMAGE_PATH, $validatedStore['store_logo']);
            throw $e;
        }
    }

    public function updateStorePackage(Store $store, $validatedData){

          $store->update([
              'store_type_code' => $validatedData['store_type_code'],
              'registration_charge' => $validatedData['registration_charge'],
              'refundable_registration_charge' => $validatedData['refundable_registration_charge'],
              'base_investment' => $validatedData['base_investment'],
              'store_type_package_history_code' => $validatedData['store_type_package_history_code']
          ]);

       return $store->refresh();

    }



    public function delete($store)
    {
        $store->delete();
        return $store;
    }

    public function syncStoreWarehouses($store, $validated)
    {
        // foreach($validated)
        $store->warehouses()->sync($validated);
        return $store;
    }

    public function updateStatus($store,$validateStatusData)
    {
       $store->update(  [
           'status'=> $validateStatusData['status'],
           'remarks'=>$validateStatusData['remarks']
       ]);
       return $store;
    }



    public function getAllActiveStore()
    {
        return Store::where('is_active',1)
            ->where('status','approved')
            ->select('store_code','store_name')
            ->get();
    }

    public function enablePurchasingPower($store)
    {
         $store->update([
            'has_purchase_power'=>1
        ]);
         return $store->refresh();
    }

    public function changeStoreStatusToApproved($store)
    {
         $store->update([
            'status'=>'approved'
        ]);

        return $store->refresh();
    }

    public function getStoreByReferralCode($referredBy,$paginatedBy = 10)
    {
        return  Store::where('referred_by',$referredBy)
              ->latest()->paginate($paginatedBy);
    }

    public function togglePurchasePower(Store $store){
          return $store->update([
              'has_purchase_power' => $store->has_purchase_power ? 0 : 1
          ]);
    }

    public function changeStoreStatus(Store $store,$status){

        try{

            $store->is_active = $status;
            $store->save();

            return $store;
        }catch (Exception $exception){
            throw $exception;
        }

    }

    public function updateStoreMapLocation($validatedStore, $store)
    {
        try {
            $store->update($validatedStore);
            return $store->fresh();
        } catch (Exception $e) {

            throw $e;
        }
    }
    public function updateStoreReferredIncentiveAmountOfSalesManager(Store $store,$referredIncentiveAmount){
        try{
            $store->update(['referred_incentive_amount'=>$referredIncentiveAmount]);
            return $store->fresh();
        }catch (Exception $e){
            throw $e;
        }
    }

    public function updatePhoneVerificationStatus(Store $store)
    {
        return $store->update([
            'phone_verified_at' => Carbon::now()
        ]);
    }
    public function updateEmailVerificationStatus(Store $store){
        return $store->update([
            'email_verified_at' => Carbon::now()
        ]);
    }
}
