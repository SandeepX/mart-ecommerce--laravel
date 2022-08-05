<?php

namespace App\Modules\SalesManager\Repositories;

use App\Modules\SalesManager\Models\Manager;
use App\Modules\Store\Models\Store;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ManagerRepository
{
    public function getAllManagersLists($select = '*',$with=[]){
        return Manager::select($select)->with($with)->latest()->get();
    }
    public function findOrFailManagerByCode($managerCode,$with=[]){
        return Manager::with($with)->where('manager_code',$managerCode)->firstorFail();
    }

    public function findManagerByCode($managerCode){
        return Manager::where('manager_code',$managerCode)->first();
    }
    public function findOrFailSalesManagerByUserCode($managerCode){
        $manager = $this->findManagerByCode($managerCode);
        if(!$manager){
            throw new ModelNotFoundException('No Such Sales Manager Found');
        }
        return $manager;
    }

    public function storeManagerDetail($validatedData){
        $superAdminUserCode = User::getSuperAdminUserCode();
        $validatedData['created_by']= $superAdminUserCode;
        $validatedData['updated_by']= $superAdminUserCode;
        $validatedData['is_active'] = 1;
        return Manager::create($validatedData)->fresh();
    }

    public function updateManagerDetail(Manager $manager,$validatedData){
        $manager->update($validatedData);
        return $manager->refresh();
    }

    public function getStoreByReferralCode($referredBy,$paginatedBy = 10)
    {
//        return  Manager::where('referred_by',$referredBy)
//                         ->latest()
//                         ->paginate($paginatedBy);
    }

    public function findManagerCodeByReferralCode($referredCode)
    {
        $manager = Manager::where('referral_code',$referredCode)->first();
        return $manager;
    }

    public function updatePhoneVerificationStatus(Manager $manager)
    {
        return $manager->update([
            'phone_verified_at' => Carbon::now()
        ]);
    }
    public function updateEmailVerificationStatus(Manager $manager){
        return $manager->update([
            'email_verified_at' => Carbon::now()
        ]);
    }

}
