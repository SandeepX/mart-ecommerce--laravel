<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/11/2020
 * Time: 12:18 PM
 */

namespace App\Modules\User\Repositories;


use App\Modules\AlpasalWarehouse\Models\Warehouse;
use App\Modules\User\Models\User;

use Exception;

class WarehouseUserRepository
{

    public function findOrFailUserByWarehouseCode($warehouseCode,$userCode,$with=[]){
        return User::with($with)->whereHas('warehouseUser',function ($q) use ($warehouseCode){
            $q->where('warehouse_code',$warehouseCode);
        })->where('user_code',$userCode)->firstOrFail();

    }

    public function getUsersByWarehouseCode($warehouseCode){

        $users = User::whereHas('warehouseUser',function ($q) use ($warehouseCode){
            $q->where('warehouse_code',$warehouseCode);
        })->latest()->get();

        return $users;
    }

    public function addUserToWarehouse(Warehouse $warehouse,User $user){

        //$warehouse->users()->attach([$user->user_code]);

        $warehouse->warehouseUsers()->create(
            [
                'user_code'=>$user->user_code
            ]
        );
    }

    public function getWarehouseOfUser(User $user){

        if(isset($user->warehouseUser) && isset($user->warehouseUser->warehouse)){

            return $user->warehouseUser->warehouse;
        }

        throw new Exception("User not associated with any warehouse");

    }
}
