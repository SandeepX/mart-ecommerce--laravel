<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/9/2020
 * Time: 4:10 PM
 */

namespace App\Modules\AlpasalWarehouse\Services;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseRepository;

use App\Modules\RolePermission\Repositories\RoleRepository;
use App\Modules\Types\Repositories\UserTypeRepository;
use App\Modules\User\Jobs\SendWelcomeEmailJob;
use App\Modules\User\Mails\WelcomeEmail;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\UserRepository;

use App\Modules\User\Repositories\UserRoleRepository;
use App\Modules\User\Repositories\WarehouseUserRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class UserWarehouseService
{

    private $userTypeRepository;
    private $userRepository;
    private $warehouseRepository;
    private $userRoleRepository;
    private $warehouseUserRepository;
    private $roleRepository;

    public function __construct(UserTypeRepository $userTypeRepository,
                                UserRepository $userRepository,
                                WarehouseRepository $warehouseRepository,
                                UserRoleRepository $userRoleRepository,
                                WarehouseUserRepository $warehouseUserRepository,
                                RoleRepository $roleRepository)
    {
        $this->userTypeRepository = $userTypeRepository;
        $this->userRepository = $userRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->userRoleRepository = $userRoleRepository;
        $this->warehouseUserRepository = $warehouseUserRepository;
        $this->roleRepository = $roleRepository;
    }

    public function getUsersByWarehouseCode($warehouseCode){

        return $this->warehouseUserRepository->getUsersByWarehouseCode($warehouseCode);
    }

    public function getWarehouseOfUser(User $user){
        return $this->warehouseUserRepository->getWarehouseOfUser($user);
    }

    public function findOrFailUserByWarehouseCode($warehouseCode,$userCode,$with=[]){
        return $this->warehouseUserRepository->findOrFailUserByWarehouseCode($warehouseCode,$userCode,$with);
    }

    public function storeWarehouseUserWithRole(Warehouse $warehouse,$validatedUser,$userType){

        try{
            //user create
            $warehouseUserType = $this->userTypeRepository->findOrFailUserTypeBySlug($userType);
            $validatedUser['user_type_code'] = $warehouseUserType->user_type_code;
            $validatedUser['password'] = uniqueHash();
            $user = $this->userRepository->create($validatedUser);

            $this->userRoleRepository->updateUserRole($user,$validatedUser['role_id']);

            $this->warehouseUserRepository->addUserToWarehouse($warehouse,$user);

            //dispatching welcome mail
            $data = [
                'user' => $user,
                'login_password' => $validatedUser['password'],
                'user_type' => 'Warehouse',
                // 'login_link' => config('site_urls.ecommerce_site')."/warehouse-login"
                'login_link' => route('warehouse.login')
            ];

            SendWelcomeEmailJob::dispatch($user,new WelcomeEmail($data));

            return $user;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function storeWarehouseWithUser($validatedWarehouse,$validatedUser,$userType){

        try {
            //warehouse create
            $warehouse = $this->warehouseRepository->storeWarehouse($validatedWarehouse);

            $user = $this->storeWarehouseUserWithRole($warehouse,$validatedUser,$userType);

        } catch (Exception $exception) {
            throw  $exception;
        }
        return [
            'warehouse'=>$warehouse,
            'user' => $user,
        ];
    }

    public function updateWarehouseUserWithRole($validated,$userCode)
    {
        try {
            $user = $this->warehouseUserRepository->findOrFailUserByWarehouseCode(getAuthWarehouseCode(),$userCode);
            if($user->userType->slug === 'warehouse-admin')
            {

                throw new Exception('Only Warehouse User Role Can be Updated');
            }
            DB::beginTransaction();
            $this->userRepository->update($validated, $user);
            $this->userRoleRepository->updateUserRole($user,$validated['role_id']);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $user;
    }

    public function deleteWarehouseUser($userCode)
    {
        try {
            if (getAuthUserCode() == $userCode){
                throw new Exception('Cannot delete self.');
            }
            $user = $this->warehouseUserRepository->findOrFailUserByWarehouseCode(getAuthWarehouseCode(),$userCode);
            if($user->userType->slug === 'warehouse-admin')
            {
                throw new Exception('Only Warehouse User Role Can be Deleted');
            }
            DB::beginTransaction();
            $this->userRepository->delete($user);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $user;
    }

    public function toggleWarehouseUserStatus($userCode)
    {
        try {
            if (getAuthUserCode() == $userCode){
                throw new Exception('Cannot update status of self.');
            }
            $user = $this->warehouseUserRepository->findOrFailUserByWarehouseCode(getAuthWarehouseCode(),$userCode);
            if($user->userType->slug === 'warehouse-admin')
            {
                throw new Exception('Only Warehouse User Role Can be Updated');
            }
            DB::beginTransaction();
            $user->is_active == 1?$data['is_active'] =0 :$data['is_active']=1;
            $this->userRepository->updateActiveStatus($user,$data);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $user;
    }
   //done by Govinda
    public function storeWarehouseWithUserByAdmin($validatedWarehouse,$validatedUser,$userType){

        try {
            //warehouse create
            $warehouse = $this->warehouseRepository->storeWarehouse($validatedWarehouse);

            $user = $this->storeWarehouseUserWithoutRole($warehouse,$validatedUser,$userType);

        } catch (Exception $exception) {
            throw  $exception;
        }
        return [
            'warehouse'=>$warehouse,
            'user' => $user,
        ];
    }
    public function storeWarehouseUserWithoutRole(Warehouse $warehouse,$validatedUser,$userType){

        try{
            //user create
            $warehouseUserType = $this->userTypeRepository->findOrFailUserTypeBySlug($userType);
            $validatedUser['user_type_code'] = $warehouseUserType->user_type_code;
            $validatedUser['password'] = uniqueHash();
            $user = $this->userRepository->create($validatedUser);
            $roleId = $this->roleRepository->findRoleBySlug('warehouse-admin');
            $this->userRoleRepository->updateUserRole($user,array($roleId->id));

            $this->warehouseUserRepository->addUserToWarehouse($warehouse,$user);
            //dispatching welcome mail
            $data = [
                'user' => $user,
                'login_password' => $validatedUser['password'],
                'user_type' => 'Warehouse',
                // 'login_link' => config('site_urls.ecommerce_site')."/warehouse-login"
                'login_link' => route('warehouse.login')
            ];

            SendWelcomeEmailJob::dispatch($user,new WelcomeEmail($data));

            return $user;
        }catch (Exception $exception){
            throw $exception;
        }
    }
}
