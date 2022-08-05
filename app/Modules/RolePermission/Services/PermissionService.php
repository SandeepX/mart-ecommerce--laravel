<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/5/2020
 * Time: 2:22 PM
 */

namespace App\Modules\RolePermission\Services;


use App\Modules\RolePermission\Repositories\PermissionRepository;

class PermissionService
{

    private $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getAllPermissions(){

        return $this->permissionRepository->getAll();
    }

    public function getCategoryGroupedPermissions(){
        return $this->permissionRepository->getAll()->groupBy('category')->sortKeys();
    }

    public function getRolewisePermissions($permissionFor){
        return $this->permissionRepository->getRolewisePermissions($permissionFor)->groupBy('category')->sortKeys();
    }
}
