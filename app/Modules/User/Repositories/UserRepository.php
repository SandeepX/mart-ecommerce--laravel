<?php


namespace App\Modules\User\Repositories;
use App\Modules\SalesManager\Models\ManagerStore;
use App\Modules\User\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Exception;
use Illuminate\Support\Facades\Auth;


class UserRepository
{

    public function getAllUsers(){
        return User::with('userType')->latest()->get();
    }

    public function getAdminTypeUsers(){
        return User::whereHas('userType',function($query){
            $query->whereIn('slug',['admin', 'super-admin']);
        })->latest()->get();
    }

    public function getVendorTypeUsers(){
        return User::whereHas('userType',function($query){
            $query->where('slug','vendor');
        })->latest()->get();
    }


    public function getVendorAdmins($with=[]){
        return User::with($with)->whereHas('userType',function($query){
            $query->where('slug','vendor');
        })->latest()->get();
    }

    public function getStoreTypeUsers(){
        return User::whereHas('userType',function($query){
            $query->where('slug','store');
        })->latest()->get();
    }

    public function findUserByCode($userCode){
        return User::where('user_code',$userCode)->first();
    }

    public function findUserByReferralCode($referralCode,$select='*'){
        return User::where('referral_code',$referralCode)
            ->select($select)
            ->first();
    }

    public function findUserByID($userID){
        return User::where('user_code',$userID)->first();
    }


    public function findOrFailUserByCode($userCode,$with=[]){
        return User::with($with)->where('user_code',$userCode)->first();
    }

    public function findUserByEmail($email,$with=[]){
       return User::with($with)->where('login_email',$email)->first();
    }

    public function findOrFailUserByEmail($email,$with=[]){

        $user = $this->findUserByEmail($email,$with);
        if(!$user){
           throw new Exception('Invalid Credentials :(');
        }
        return $user;

    }

    public function findUserByPhone($phone,$with=[]){
        return User::with($with)->where('login_phone',$phone)->first();
    }

    public function findOrFailUserByPhone($phone,$with=[]){

        $user = $this->findUserByPhone($phone,$with);
        if(!$user){
            throw new Exception('Invalid Credentials :(');
        }
        return $user;

    }

    public function findOrFailUserByID($userID){
        if(!$user = $this->findUserByID($userID)){
            throw new ModelNotFoundException('No Such User Found');
        }
        return $user;
    }


    public function create($validated){
        $validated['password'] = bcrypt($validated['password']);
        $validated['user_code'] = User::generateUserCode();
        $validated['created_by']= getAuthUserCode();
        $validated['updated_by']= getAuthUserCode();
        $validated['is_first_login'] = 0;
        return User::create($validated)->fresh();

    }
    public function createFromApi($validated){

        $validated['password'] = bcrypt($validated['password']);
        $validated['user_code'] = User::generateUserCode();
        $validated['created_by'] =  User::getSuperAdminUserCode();
        $validated['updated_by'] =  User::getSuperAdminUserCode();


        return User::create($validated)->fresh();

    }

    public function update($validated, $user)
    {
        $validated['updated_by'] = Auth::check() ? getAuthUserCode(): getSuperAdminUserCode();
        $user->update($validated);
        return $user;

    }

    public function updateLoginDetail($user, $data){

       $user->last_login_ip = $data['last_login_ip'];
       $user->last_login_at = $data['last_login_at'];
       $user->save();
        return $user;

    }

    public function updateUserPassword(User $user,$newPassword){

        try{
            // $user->password = Hash::make($newPassword);
            $user->password =bcrypt($newPassword);

            $user->save();

            return $user;
        }catch (Exception $e){
            throw $e;
        }

    }

    public function delete($user) {
        $user->delete();
        $user->deleted_by = getAuthUserCode();
        $user->save();
        return $user;
    }


    public function toggleActiveStatus(User $user){
        return $user->update([
            'is_active' => !$user->is_active,
        ]);
    }

    public function updateActiveStatus(User $user,$validated){


        $authUserCode = getAuthUserCode();
        // $validated['updated_by'] = $authUserCode;
        $user->updated_by=$authUserCode;
        $user->is_active=$validated['is_active'];
        $user->save();
        return $user;
    }

    public function findManagerCodeByReferralCode($referredBy)
    {
        $managerCode = User::where('referral_code',$referredBy)->first();
        return $managerCode;
    }

    public function updatePhoneVerificationStatus($user)
    {
        return $user->update([
           'phone_verified_at' => Carbon::now()
        ]);

    }
    public function updateEmailVerificationStatus($user){
        return $user->update([
            'email_verified_at' => Carbon::now()
        ]);
    }

    public function findOrFailSalesManagerByUserCode($userCode){
       $user = $this->findUserByCode($userCode);

       if($user->isSalesManagerUser()){
           return $user;
       }
       throw new ModelNotFoundException('No Such Sales Manager Found');
    }

    public function updateFCMTokenOfUser(User $user,$fcmToken){
        return $user->update([
            'fcm_token' => $fcmToken
        ]);
    }
}
