<?php

namespace App\Modules\SalesManager\Services;
use App\Modules\SalesManager\Events\ManagerStatusApprovedEvent;
use App\Modules\Referrals\Traits\ReferralCodeService;
use App\Modules\SalesManager\Helpers\SalesManagerFilter;
use App\Modules\SalesManager\Repositories\SalesManagerRegistrationStatusRepository;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class SalesManagerRegistrationStatusService
{
    use ReferralCodeService;
    private $salesManagerRegistrationStatusRepo;
    private $userRepository;
    public function __construct(
        SalesManagerRegistrationStatusRepository $salesManagerRegistrationStatusRepo,
        UserRepository $userRepository
    )
    {
       $this->salesManagerRegistrationStatusRepo = $salesManagerRegistrationStatusRepo;
       $this->userRepository = $userRepository;
    }

    public function updateStatusByUserCode($validatedData,$userCode){


        try{
            DB::beginTransaction();
            $user = $this->userRepository->findOrFailUserByCode($userCode);
            if($validatedData['status'] === 'approved'){
                $validatedUserData = [];
                $referralCode = $this->generateReferralCode($user);
                if(SalesManagerFilter::checkManagerReferralCodeExists($referralCode,$userCode)){
                    throw new Exception('Referral code already exists cannot approve');
                }
                $validatedUserData['referral_code'] = $referralCode;
                $user =  $this->userRepository->update($validatedUserData,$user);
                event(new ManagerStatusApprovedEvent($user));

            }



            $salesManagerRegistrationStatus = $this->salesManagerRegistrationStatusRepo->findortFailUserRegistrationStatusByUserCode($userCode);
            $this->salesManagerRegistrationStatusRepo->updateStatus($salesManagerRegistrationStatus,$validatedData);

            DB::commit();
        }catch(Exception $exception){
            DB::rollback();
          throw $exception;
        }

    }
}

