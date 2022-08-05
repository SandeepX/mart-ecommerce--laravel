<?php

namespace App\Modules\User\Services;


use App\Modules\AlpasalWarehouse\Services\UserWarehouseService;
use App\Modules\Types\Repositories\UserTypeRepository;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Repositories\UserRoleRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Hash;
use Auth;
use Exception;

class UserService
{
    private  $userRepo,$userTypeRepo,$userRoleRepository;
    private $userWarehouseService;

    public function   __construct(
        UserRepository $userRepo,
        UserTypeRepository $userTypeRepo,
        UserRoleRepository $userRoleRepository,
        UserWarehouseService $userWarehouseService
    )
    {
        $this->userRepo = $userRepo;
        $this->userTypeRepo = $userTypeRepo;
        $this->userRoleRepository = $userRoleRepository;
        $this->userWarehouseService = $userWarehouseService;
    }

    public function getAllUsers()
    {
        return $this->userRepo->getAllUsers();
    }

    public function getVendorAdmins()
    {
        return $this->userRepo->getVendorAdmins(['vendor']);
    }

    public function getVendorTypeUsers()
    {
        return $this->userRepo->getVendorTypeUsers();
    }

    public function getUsersOfOwnWarehouse(){
        try{
            $warehouse = $this->userWarehouseService->getWarehouseOfUser(auth()->user());

            $users = $this->userWarehouseService->getUsersByWarehouseCode($warehouse->warehouse_code);

            return $users;

        }catch (Exception $exception){
            throw $exception;
        }

    }

    public function getAdminTypeUsers()
    {
        return $this->userRepo->getAdminTypeUsers();
    }

    public function findUserByCode($userCode){
        return $this->userRepo->findUserByCode($userCode);
    }

    public function findUserByID($userID){
        return $this->userRepo->findUserByID($userID);
    }

    public function findOrFailUserByCode($userCode){
        return $this->userRepo->findOrFailUserByCode($userCode);
    }
    public function findOrFailUserByCodeWith($userCode,array $with){
        return $this->userRepo->findOrFailUserByCode($userCode,$with);
    }

    public function findOrFailUserByID($userID){
        return $this->userRepo->findOrFailUserByID($userID);
    }


    public function storeUser($validated)
    {

        DB::beginTransaction();
        try {
            $user = $this->userRepo->create($validated);
            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $user;
    }

    public function storeAdmin($validated)
    {
        $adminUserTypeCode = $this->userTypeRepo->findAdminUserType();
        $validated['user_type_code'] = $adminUserTypeCode->user_type_code;
        return $this->storeUser($validated);
    }

    public function storeAdminWithRole($validated)
    {
        try{
            $adminUserTypeCode = $this->userTypeRepo->findOrFailAdminUserType();
            $validated['user_type_code'] = $adminUserTypeCode->user_type_code;
            DB::beginTransaction();
            $user = $this->userRepo->create($validated);
            $this->userRoleRepository->updateUserRole($user,$validated['role_id']);
            DB::commit();
            return $user;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function storeWarehouseUserWithRole($validatedUser)
    {
        try{
            $warehouse = $this->userWarehouseService->getWarehouseOfUser(auth()->user());
            DB::beginTransaction();
            $user = $this->userWarehouseService->storeWarehouseUserWithRole($warehouse,$validatedUser,'warehouse-user');
            DB::commit();
            return $user;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function storeVendorUser($validated)
    {
        $vendorUserTypeCode = $this->userTypeRepo->findVendorUserType();
        $validated['user_type_code'] = $vendorUserTypeCode->user_type_code;
        return $this->storeUser($validated);
    }

    public function storeStoreUser($validated)
    {
        $storeUserTypeCode = $this->userTypeRepo->findStoreUserType();
        $validated['user_type_code'] = $storeUserTypeCode->user_type_code;
        return $this->storeUser($validated);
    }

    public function updateUser($validated, $userCode)
    {
        DB::beginTransaction();

        try {
            $user = $this->findOrFailUserByCode($userCode);
            $this->userRepo->update($validated, $user);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw  $exception;
        }
        return $user;
    }

    public function updateUserWithRole($validated,$userCode)
    {
        try {
            $user = $this->findOrFailUserByCode($userCode);
            DB::beginTransaction();
            $this->userRepo->update($validated, $user);
            $this->userRoleRepository->updateUserRole($user,$validated['role_id']);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $user;
    }

    public function updateLastLoginDetail(User $user){

        try{
            DB::beginTransaction();
            $data=[
                'last_login_ip' => request()->ip(),
                'last_login_at' => Carbon::now()
            ];
            $this->userRepo->updateLoginDetail($user,$data);

            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw  $exception;
        }
    }


    public function updateUserPassword(User $user,$inputData){

        try{
            $currentInputPassword = $inputData['current_password'];

            if (Hash::check($currentInputPassword, $user->password)) {

                $newPassword =$inputData['password'];

                //dd($newPassword);
                DB::beginTransaction();
                $user = $this->userRepo->updateUserPassword($user,$newPassword);
                DB::commit();

                Auth::logout();

            } else {
                throw new Exception('The Current Password did not match!');
            }

        }catch (Exception $e){

            DB::rollBack();
            throw $e;
        }
    }



    public function updateAdminPassword(User $user,$newPassword){

        try{

                //dd($newPassword);
                DB::beginTransaction();
                $user = $this->userRepo->updateUserPassword($user,$newPassword);
                DB::commit();

        }catch (Exception $e){

            DB::rollBack();
            throw $e;
        }


    }

    public function deleteUser($userCode)
    {
        DB::beginTransaction();
        try {
            if (getAuthUserCode() == $userCode){
                throw new Exception('Cannot delete self.');
            }
            $user = $this->findOrFailUserByCode($userCode);
            $this->userRepo->delete($user);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $user;
    }


    public function storeStoreUserFromApi($validated)
    {
//        DB::beginTransaction();
        try {
            $user = $this->userRepo->createFromApi($validated);
//            DB::commit();
        } catch (\Exception $exception) {
//            DB::rollBack();
            throw  $exception;
        }
        return $user;
    }

    public function toggleActiveStatus($userCode){

        try{
            $user= $this->userRepo->findOrFailUserByCode($userCode);
            DB::beginTransaction();
            $this->userRepo->toggleActiveStatus($user);
            DB::commit();
        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function findManagerCodeByReferralCode($referredBy)
    {
        return $this->userRepo->findManagerCodeByReferralCode($referredBy);
    }

    public function updateFCMTokenOfUser(User $user,$fcmToken){
        try{
            DB::beginTransaction();
            $this->userRepo->updateFCMTokenOfUser($user,$fcmToken);
            DB::commit();
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

}
