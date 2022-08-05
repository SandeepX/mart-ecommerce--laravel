<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/5/2020
 * Time: 5:22 PM
 */

namespace App\Modules\RolePermission\Repositories;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\Permission\Models\Role;

class RoleRepository
{
    public static function getUserTypes(){
        return [
            'Admin'=>'admin',
            'Warehouse' =>'warehouse'
        ];
    }

    public function getAll(){

        return Role::where('is_closed',0)->latest()->get();
    }

    public function getByUserType($userType){
        return Role::where('for_user_type',$userType)->where('is_closed',0)->latest()->get();
    }

    public function findById($id,$with=[]){
        return Role::with($with)->find($id);
    }

    public function findOrFailById($id,$with=[]){

        $role = $this->findById($id,$with);

        if (!$role){
            throw new ModelNotFoundException('Role not found');
        }

        return $role;
    }


    public function save($data){

        $role = Role::create([
            'name' =>$data['name'],
            'slug' =>$data['slug'],
            'is_closed' => 0,
            'for_user_type' => $data['for_user_type'],
            'description' => $data['description'],
            'created_by' =>getAuthUserCode(),
            'updated_by' =>getAuthUserCode(),
            'guard_name' => 'web',
        ]);

        //$role->permissions()->sync($data['permission_id']);

        return $role;
    }

    public function updatePermissions(Role $role,array $permissionsId){

        $role->permissions()->sync($permissionsId);

        return $role;
    }

    public function update($data,Role $role){

        $role->update([
            'name' =>$data['name'],
            'slug' =>$data['slug'],
            'for_user_type' => $data['for_user_type'],
            'description' => $data['description'],
            'updated_by' =>getAuthUserCode(),
        ]);

        return $role;
    }

    public function delete(Role $role) {
        $role->delete();
        return $role;
    }
    public function findRoleBySlug($slug)
    {
       return Role::where('slug',$slug)->first();
    }
    public function findRoleByUserType($userType)
    {
        return Role::where('for_user_type',$userType)->first();
    }
}
