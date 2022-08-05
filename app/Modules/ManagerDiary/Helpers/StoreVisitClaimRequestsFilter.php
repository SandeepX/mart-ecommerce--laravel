<?php

namespace App\Modules\ManagerDiary\Helpers;

use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimRequestByManager;

class StoreVisitClaimRequestsFilter
{
    public static function filterStoreVisitClaimRequestOfManager($filterParameters,$paginateBy=20,$with = []){
        $referredByOperator = (isset($filterParameters['is_referred']) && $filterParameters['is_referred']=='yes') ? '!=' : ((isset($filterParameters['is_referred']) && $filterParameters['is_referred']=='no' ) ? '=' : NULL);

        $storeVisitClaimRequests = StoreVisitClaimRequestByManager::with($with)
                                   ->when(isset($filterParameters['manager_code']),function ($query) use ($filterParameters){
                                        $query->whereHas('managerDiary',function ($q) use ($filterParameters){
                                            $q->where('manager_code',$filterParameters['manager_code']);
                                        });
                                   })
                                   ->when(isset($filterParameters['store_name']),function ($query) use ($filterParameters){
                                       $query->whereHas('managerDiary',function ($q) use ($filterParameters){
                                           $q->where('store_name','like','%'.$filterParameters['store_name']. '%');
                                       });
                                   })
                                   ->when(isset($filterParameters['owner_name']),function ($query) use ($filterParameters){
                                        $query->whereHas('managerDiary',function ($q) use ($filterParameters) {
                                            $q->where('owner_name', 'like', '%' . $filterParameters['owner_name'] . '%');
                                        });
                                   })
                                    ->when(isset($filterParameters['phone_no']),function ($query) use ($filterParameters){
                                        $query->whereHas('managerDiary',function ($q) use ($filterParameters) {
                                            $q->where(function ($query) use ($filterParameters) {
                                                $query->where('phone_no', $filterParameters['phone_no'])
                                                    ->orWhere('alt_phone_no', $filterParameters['phone_no']);
                                            });
                                        });
                                    })
                                    ->when(isset($filterParameters['pan_no']),function ($query) use ($filterParameters){
                                        $query->whereHas('managerDiary',function ($q) use ($filterParameters) {
                                            $q->where(function ($query) use ($filterParameters) {
                                                $query->where('pan_no', $filterParameters['pan_no']);
                                            });
                                        });
                                    })
                                   ->when($referredByOperator && isset($filterParameters['is_referred']),function ($query) use ($filterParameters,$referredByOperator){
                                       $query->whereHas('managerDiary',function ($q) use ($filterParameters,$referredByOperator) {
                                           $q->where('referred_store_code', $referredByOperator, NULL);
                                       });
                                   })
                                   ->when(isset($filterParameters['status']),function ($query) use ($filterParameters){
                                       $query->where('status',$filterParameters['status']);
                                   })
                                    ->when(isset($filterParameters['amount_from']),function ($query) use ($filterParameters){
                                        $query->whereHas('managerDiary',function ($q) use ($filterParameters) {
                                            $q->where('business_investment_amount', '>=', $filterParameters['amount_from']);
                                        });
                                    })
                                    ->when(isset($filterParameters['amount_to']),function ($query) use ($filterParameters){
                                        $query->whereHas('managerDiary',function ($q) use ($filterParameters) {
                                            $q->where('business_investment_amount', '<=', $filterParameters['amount_to']);
                                        });
                                    })
                                   ->when(isset($filterParameters['date_from']),function ($query) use($filterParameters){
                                        $query->whereDate('created_at','>=',date('y-m-d',strtotime($filterParameters['date_from'])));
                                   })
                                   ->when(isset($filterParameters['date_to']),function ($query) use($filterParameters){
                                        $query->whereDate('created_at','<=',date('y-m-d',strtotime($filterParameters['date_to'])));
                                   });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;
        $storeVisitClaimRequests = $storeVisitClaimRequests->latest()->paginate($paginateBy);

        return $storeVisitClaimRequests;
    }
}
