<?php

namespace App\Modules\SalesManager\Repositories;

use App\Modules\SalesManager\Models\SalesManagerRegistrationStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Notification;

class SalesManagerRegistrationStatusRepository
{

    public function findortFailUserRegistrationStatusByUserCode($userCode){
       return SalesManagerRegistrationStatus::where('user_code',$userCode)
           ->latest()
           ->firstorFail();
    }

    public function storeRegistrationStatus($validatedData){
        return SalesManagerRegistrationStatus::create($validatedData);
    }

    public function updateStatus($salesManagerRegistrationStatus,$validatedData){
      return $salesManagerRegistrationStatus->update($validatedData);
    }

    public function getUserDetailByCode($userAuthCode)
    {
        return SalesManagerRegistrationStatus::where('user_code',$userAuthCode)
            ->where('status','approved')
            ->firstorfail('assigned_area_code');
    }

}
