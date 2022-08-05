<?php


namespace App\Modules\SalesManager\Repositories;
use App\Modules\SalesManager\Models\ManagerStore;
use Exception;


class ManagerStoreRepository
{


    public function getAllstoreByManagerCode($managerCode)
    {
        try{
            return ManagerStore::where('manager_code',$managerCode)->get();
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function findorfailAssignedStoreByManagerStoreCode($managerStoreCode)
    {
        try{
            return ManagerStore::where('manager_store_code',$managerStoreCode)->firstorFail();
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function assignStoreWithManager($validateData)
    {
        try{
           return ManagerStore::updateOrCreate([
               'manager_code' => $validateData['manager_code'],
               'store_code' => $validateData['store_code']
           ]
           );

        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function deleteAssignedStore($storeManagerDetail)
    {
        try{
            return $storeManagerDetail->delete();
        }catch(Exception $exception){
            throw $exception;
        }
    }




}
