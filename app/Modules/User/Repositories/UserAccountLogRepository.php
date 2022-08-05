<?php


namespace App\Modules\User\Repositories;


use App\Modules\User\Models\UserAccountLog;

class UserAccountLogRepository
{

    public function findUserAccountSuspendLog($userCode){
       return UserAccountLog::where('account_code',$userCode)
                             ->where('account_status','suspend')
                             ->where('is_closed',0)
                             ->latest()
                             ->first();
    }
    public function findUserAccountBannedLog($userCode){
        return UserAccountLog::where('account_code',$userCode)
            ->where('account_status','permanently_banned')
            ->where('is_closed',0)
            ->latest()
            ->first();
    }

    public function getUserAccountLogsByUserCode($userCode,$with=[]){
        $userAccountLogs = UserAccountLog::with($with)
            ->where('account_code',$userCode)
            ->latest()
            ->get();
        return $userAccountLogs;
    }

    public function storeSuspendUserDetail($validatedData){
       return UserAccountLog::create($validatedData);
    }

    public function updateUnsuspendData($userSuspend,$data){
       return $userSuspend->update($data);
    }

    public function storeBannedUserDetail($validatedData){
        return UserAccountLog::create($validatedData);
    }

    public function updateUnBanData($userBan,$data){
          return $userBan->update($data);
    }

}
