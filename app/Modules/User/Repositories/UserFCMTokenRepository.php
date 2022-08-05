<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\Models\UserFCMToken;

class UserFCMTokenRepository
{

    public function getFcmTokenOfUserByUserCode($userCode){
      return UserFCMToken::where('user_code',$userCode)->get();
    }

    public function saveUserFCMToken($validatedData){
     return UserFCMToken::create($validatedData);
    }

}
