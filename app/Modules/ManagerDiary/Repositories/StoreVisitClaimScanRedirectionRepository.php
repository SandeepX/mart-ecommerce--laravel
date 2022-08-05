<?php

namespace App\Modules\ManagerDiary\Repositories;

use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimScanRedirection;
use Exception;

class StoreVisitClaimScanRedirectionRepository
{
    public function getAllPaginatedStoreVisitClaimRedirection($paginateBy = 10){
        return StoreVisitClaimScanRedirection::latest()->paginate($paginateBy);
    }

    public function getActiveStoreVisitClaimRedirection(){
        return StoreVisitClaimScanRedirection::where('is_active',1)->latest()->get();
    }

    public function findStoreVisitScanRedirectionByCode($storeVisitClaimScanRedirectionCode,$with=[]){
        return StoreVisitClaimScanRedirection::with($with)
                                              ->where('store_visit_claim_scan_redirection_code',$storeVisitClaimScanRedirectionCode)
                                              ->first();
    }

    public function findOrFailStoreVisitScanRedirectionByCode($storeVisitClaimScanRedirectionCode,$with = []){
      $storeVisitClaimScanRedirect = $this->findStoreVisitScanRedirectionByCode($storeVisitClaimScanRedirectionCode,$with);
      if(!$storeVisitClaimScanRedirect){
          throw new Exception('Store Scan Redirection not found');
      }
      return $storeVisitClaimScanRedirect;
    }

    public function save($validatedData){
         $visitClaimRedirection = StoreVisitClaimScanRedirection::create($validatedData);
         return $visitClaimRedirection->fresh();
    }

    public function update(StoreVisitClaimScanRedirection $storeVisitClaimScanRedirection,$validatedData){
        $storeVisitClaimScanRedirection->update($validatedData);
        return $storeVisitClaimScanRedirection->refresh();
    }

    public function delete(StoreVisitClaimScanRedirection $storeVisitClaimScanRedirection) {
        $storeVisitClaimScanRedirection->delete();
        return $storeVisitClaimScanRedirection;
    }

}
