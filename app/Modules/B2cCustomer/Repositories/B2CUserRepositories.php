<?php


namespace App\Modules\B2cCustomer\Repositories;

use App\Modules\B2cCustomer\Models\B2CUserRegistrationStatus;

class B2CUserRepositories
{

    public function findortFailUserRegistrationStatusByUserCode($userCode)
    {
        return B2CUserRegistrationStatus::where('user_code', $userCode)
            ->latest()
            ->firstorFail();
    }

    public function storeRegistrationStatus($validatedData)
    {
        return B2CUserRegistrationStatus::create($validatedData);
    }

    public function updateStatus($userB2CRegistrationStatus, $validatedData)
    {
        return $userB2CRegistrationStatus->update($validatedData);
    }


}

