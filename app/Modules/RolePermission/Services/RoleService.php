<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/5/2020
 * Time: 5:20 PM
 */

namespace App\Modules\RolePermission\Services;


use App\Modules\RolePermission\Repositories\RoleRepository;
use App\Modules\User\Repositories\UserRoleRepository;
use Exception;
use DB;

class RoleService
{

    private $rolePermissionService,$roleRepository,$userRoleRepository;

    public function __construct(RolePermissionService $rolePermissionService,
                                RoleRepository $roleRepository,UserRoleRepository $userRoleRepository)
    {
        $this->rolePermissionService = $rolePermissionService;
        $this->roleRepository = $roleRepository;
        $this->userRoleRepository = $userRoleRepository;
    }

    public static function getRoleUserTypes(){

        return RoleRepository::getUserTypes();
    }

    public function getAllRoles(){
        return $this->roleRepository->getAll();
    }

    public function getGeneralAdminTypeRoles(){

        return $this->roleRepository->getByUserType('admin');
    }

    public function getWarehouseTypeRoles(){

        return $this->roleRepository->getByUserType('warehouse');
    }

    public function findRoleById($id){

        return $this->roleRepository->findById($id);
    }

    public function findOrFailRoleById($id){

        return $this->roleRepository->findOrFailById($id);
    }

    public function findRoleByIdWith($id,array $with){

        return $this->roleRepository->findById($id,$with);
    }

    public function findOrFailRoleByIdWith($id,array $with){

        return $this->roleRepository->findOrFailById($id,$with);
    }

    public function findOrFailRoleByIdWithPermission($id){

        return $this->roleRepository->findOrFailById($id,'permissions');
    }

    public function storeRole($validatedData){

        return $this->rolePermissionService->saveRoleWithPermission($validatedData);
    }

    public function updateRole($validatedData,$roleId){

        return $this->rolePermissionService->updateRoleWithPermission($validatedData,$roleId);
    }

    public function deleteRole($roleId){

        try{
            $role = $this->roleRepository->findOrFailById($roleId);
            if ($this->userRoleRepository->isRoleAssociatedWithUsers($roleId)){
                throw new Exception('Users associated,cannot delete role');
            }
            if($role->is_closed == 1){
                throw new Exception('Cannot delete closed role');
            }
            DB::beginTransaction();
            $role=$this->roleRepository->delete($role);
            DB::commit();
            return $role;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
    public function findRoleByUserType($userType){

        return $this->roleRepository->findRoleByUserType($userType);
    }
}
