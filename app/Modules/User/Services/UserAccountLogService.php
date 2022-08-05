<?php


namespace App\Modules\User\Services;


use App\Modules\User\Repositories\UserAccountLogRepository;
use App\Modules\User\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class UserAccountLogService
{

    private $userAccountLogRepository;

    private $userRepo;


    public function __construct(UserAccountLogRepository $userAccountLogRepository,UserRepository $userRepo){
        $this->userAccountLogRepository = $userAccountLogRepository;
        $this->userRepo = $userRepo;
    }

    public function findUserAccountSuspendLog($userCode){
        return $this->userAccountLogRepository->findUserAccountSuspendLog($userCode);
    }

    public function getUserAccountLogsByUserCode($userCode){
        return $this->userAccountLogRepository->getUserAccountLogsByUserCode($userCode);
    }

    public function storeSuspendUserDetail($userCode,$validatedData){

        try {
            $user = $this->userRepo->findOrFailUserByCode($userCode,['userType:user_type_code,slug']);

            if ($user->isSuspended()) {
                throw new Exception('Account Is Already Suspended: User Name: ' . $user->name);
            }

            if($user->isBanned()){
                throw new Exception('Account Is Already Banned Cannot Suspend: '.$user->name);
            }

            $validatedData['account_log_type'] = $user->userType->slug;
            $validatedData['account_code'] = $userCode;
            $validatedData['account_status'] = 'suspend';
            $validatedData['banned_by'] = getAuthUserCode();
            $validatedData['updated_by'] = getAuthUserCode();
             DB::beginTransaction();
              $this->userAccountLogRepository->storeSuspendUserDetail($validatedData);
             DB::commit();
        }catch(Exception $exception){
             DB::rollBack();
             throw $exception;
        }
    }

    public function unSuspendUser($userCode){

        try{
            $user = $this->userRepo->findOrFailUserByCode($userCode);
            $userSuspend = $this->userAccountLogRepository->findUserAccountSuspendLog($userCode);

            if(!$userSuspend){
                throw new Exception('Account Suspend Log Not Found To Unsuspend. User Name: '.$user->name);
            }

            if(!$user->isSuspended()){
                throw new Exception('Account Is Not Suspended To UnSuspend. User Name: '.$user->name);
            }

            if($user->isBanned()){
                throw new Exception('Account Is Banned Cannot Unsuspend. User Name: '.$user->name);
            }

            $data =[];
            $data['unbanned_by'] = getAuthUserCode();
            $data['is_unbanned'] = 1;
            $data['is_closed'] = 1;
            DB::beginTransaction();
             $this->userAccountLogRepository->updateUnsuspendData($userSuspend,$data);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function storeBannedUserDetail($userCode,$validatedData){

        try {
            $user = $this->userRepo->findOrFailUserByCode($userCode);

            if($user->isBanned()) {
                throw new Exception('Account Is Already Banned: User Name: ' . $user->name);
            }
            if($user->isSuspended()){
               throw new Exception('Account is Already Suspened To Ban: User Name: '.$user->name);
            }

            $validatedData['account_log_type'] = 'user';
            $validatedData['account_code'] = $userCode;
            $validatedData['account_status'] = 'permanently_banned';
            $validatedData['banned_by'] = getAuthUserCode();
            $validatedData['updated_by'] = getAuthUserCode();
            DB::beginTransaction();
            $this->userAccountLogRepository->storeBannedUserDetail($validatedData);
            DB::commit();
        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function unbanUser($userCode){

            try{
                $user = $this->userRepo->findOrFailUserByCode($userCode);
                $userBan = $this->userAccountLogRepository->findUserAccountBannedLog($userCode);

                if(!$userBan){
                   throw new Exception('Accout Banned Log Not Found To UnBan');
                }

                if(!$user->isBanned() ){
                    throw new Exception('Account Is Not Banned To UnBanned. User Name: '.$user->name);
                }

                if($user->isSuspended()){
                    throw new Exception('Account is Suspend Cannot UnBan. User Name: '.$user->name);
                }

                $data =[];
                $data['unbanned_by'] = getAuthUserCode();
                $data['is_unbanned'] = 1;
                $data['is_closed'] = 1;
                DB::beginTransaction();
                $this->userAccountLogRepository->updateUnBanData($userBan,$data);
                DB::commit();
            }catch (Exception $exception){
                DB::rollBack();
                throw $exception;
            }
    }





}
