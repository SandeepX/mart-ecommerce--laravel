<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/5/2020
 * Time: 2:22 PM
 */

namespace App\Modules\RolePermission\Repositories;


use Spatie\Permission\Models\Permission;

class PermissionRepository
{

    public function getAll(){

        return Permission::get();
    }

    public function getRolewisePermissions($permissionFor){

        return Permission::where('permission_for',$permissionFor)->get();
    }

}
