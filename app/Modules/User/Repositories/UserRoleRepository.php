<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/9/2020
 * Time: 2:47 PM
 */

namespace App\Modules\User\Repositories;

use App\Modules\User\Models\User;

use Illuminate\Support\Facades\DB;
class UserRoleRepository
{
    public function updateUserRole(User $user,array $rolesId){

        $user->roles()->sync($rolesId);
    }
    public function isRoleAssociatedWithUsers($roleId){

        $users = DB::table('model_has_roles')->where('role_id',$roleId)->count();

        if ($users > 0){
            return true;
        }

        return false;
    }
}
