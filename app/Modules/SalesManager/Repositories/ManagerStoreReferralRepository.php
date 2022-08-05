<?php

namespace App\Modules\SalesManager\Repositories;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\SalesManager\Models\ManagerStoreReferral;
use App\Modules\SalesManager\Models\ManagerToManagerReferrals;

class ManagerStoreReferralRepository extends RepositoryAbstract
{
    public function findOrFailByStoreCode($storeCode){
      return ManagerStoreReferral::where('referred_store_code',$storeCode)->firstOrFail()();
    }

    public function storeManagerStoreReferrals($validatedData){
        return ManagerStoreReferral::create($validatedData)->fresh();
    }


    public function getStoreByReferralCode($managerCode,$paginatedBy = 10)
    {
        return  ManagerStoreReferral::with($this->with)->where('manager_code',$managerCode)
            ->latest()->paginate($paginatedBy);
    }

    public function getReferedManagersByReferralCode($managerCode,$paginatedBy = 10)
    {
        return  ManagerToManagerReferrals::with($this->with)->where('manager_code',$managerCode)
            ->latest()->paginate($paginatedBy);
    }

    public function updateReferralDetails(ManagerStoreReferral $managerStoreReferral,$validatedData){
        $managerStoreReferral->update($validatedData);
        return $managerStoreReferral->refresh();
    }

}
