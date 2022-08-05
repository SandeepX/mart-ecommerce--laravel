<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/5/2020
 * Time: 5:21 PM
 */

namespace App\Modules\RolePermission\Services;


use App\Modules\RolePermission\Repositories\PermissionRepository;
use App\Modules\RolePermission\Repositories\RoleRepository;

use Exception;
use DB;

class RolePermissionService
{

    private $roleRepository,$permissionRepository;

    public function __construct(RoleRepository $roleRepository ,
                                PermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function saveRoleWithPermission($validatedData){

        try{
            DB::beginTransaction();

            $role =$this->roleRepository->save($validatedData);
            $role =$this->roleRepository->updatePermissions($role,$validatedData['permission_id']);

            DB::commit();
            return $role;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateRoleWithPermission($validatedData,$roleId){

        try{
            DB::beginTransaction();

            $role = $this->roleRepository->findOrFailById($roleId);
            if($role->is_closed == 1){
                throw new Exception('Cannot update closed role');
            }
            $role =$this->roleRepository->update($validatedData,$role);
            $role =$this->roleRepository->updatePermissions($role,$validatedData['permission_id']);

            DB::commit();
            return $role;
        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }

    }
}