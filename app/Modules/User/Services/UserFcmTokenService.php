<?php

namespace App\Modules\User\Services;

use App\Modules\User\Repositories\UserFCMTokenRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class UserFcmTokenService
{
    private $userFCMTokenRepository;
    public function __construct(UserFCMTokenRepository $userFCMTokenRepository)
    {
        $this->userFCMTokenRepository = $userFCMTokenRepository;
    }

    public function getFcmTokenOfUserByUserCode($userCode){
     return $this->userFCMTokenRepository->getFcmTokenOfUserByUserCode($userCode);
    }

    public function saveUserFcmTokenDetails($user,$fcmToken){
        try{
            $validatedData = [];
            $validatedData['user_code'] = $user->user_code;
            $validatedData['fcm_token'] = $fcmToken;
            DB::beginTransaction();
            $this->userFCMTokenRepository->saveUserFCMToken($validatedData);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

}
