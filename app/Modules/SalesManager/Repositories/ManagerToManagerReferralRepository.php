<?php

namespace App\Modules\SalesManager\Repositories;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\SalesManager\Models\ManagerToManagerReferrals;

class ManagerToManagerReferralRepository extends RepositoryAbstract
{
    public function createManagerToManagerReferrals($validatedData){
        $managerToManagerReferrals = ManagerToManagerReferrals::create($validatedData);
        return $managerToManagerReferrals->fresh();
    }


}
