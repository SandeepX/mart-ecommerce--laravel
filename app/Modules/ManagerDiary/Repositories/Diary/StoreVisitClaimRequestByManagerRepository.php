<?php

namespace App\Modules\ManagerDiary\Repositories\Diary;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimRequestByManager;
use Carbon\Carbon;
use Exception;

class StoreVisitClaimRequestByManagerRepository extends RepositoryAbstract
{
    public function findStoreVisitClaimRequestByCode($storeVisitClaimRequestCode,$with){
        $storeVisitClaimRequest = StoreVisitClaimRequestByManager::with($with)->where('store_visit_claim_request_code',$storeVisitClaimRequestCode)
                                                                  ->first();
        return $storeVisitClaimRequest;
    }

    public function findOrFailStoreVisitClaimRequestByCode($storeVisitClaimRequestCode,$with=[]){
        try{
            $storeVisitClaimRequest = $this->findStoreVisitClaimRequestByCode($storeVisitClaimRequestCode,$with);
            if(!$storeVisitClaimRequest){
                throw new Exception('Store Visit Claim request not found :(');
            }
            return $storeVisitClaimRequest;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function findLatestStoreVisitClaimRequestByManagerDiaryCode($managerDiaryCode){
       $storeVisitClaimRequest = StoreVisitClaimRequestByManager::where('manager_diary_code',$managerDiaryCode)
                                                                 ->latest()
                                                                 ->first();
       return $storeVisitClaimRequest;
    }

    public function findLatestStoreVisitClaimRequestOfTodayByDiaryCode($managerDiaryCode){
        $storeVisitClaimRequest = StoreVisitClaimRequestByManager::where('manager_diary_code',$managerDiaryCode)
                                                                  ->where('created_at','>=',Carbon::today())
                                                                  ->latest()
                                                                  ->first();
        return $storeVisitClaimRequest;
    }

    public function store($validatedData){
         $storeVisitClaimRequest = StoreVisitClaimRequestByManager::create($validatedData);
         return $storeVisitClaimRequest->fresh();
    }

    public function update(StoreVisitClaimRequestByManager $storeVisitClaimRequestByManager,$validatedData){
        $storeVisitClaimRequestByManager->update($validatedData);
        return $storeVisitClaimRequestByManager->refresh();
    }


}
