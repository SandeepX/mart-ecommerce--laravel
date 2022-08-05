<?php


namespace App\Modules\SalesManager\Services;
use App\Modules\SalesManager\Repositories\ManagerStoreRepository;
use App\Modules\SalesManager\Repositories\ManagerStoreHistoryRepository;
use Carbon\Carbon;
use Exception;


class AssignStoreService
{
    private $managerStoreRepo;
    private $managerStoreHistoryRepo;

    public function __construct(ManagerStoreRepository $managerStoreRepo,ManagerStoreHistoryRepository $managerStoreHistoryRepo){
        $this->managerStoreRepo = $managerStoreRepo;
        $this->managerStoreHistoryRepo = $managerStoreHistoryRepo;
    }


    public function getAllAssignedStoreByManagerCode($managerCode)
    {
        try{
            return $this->managerStoreRepo->getAllstoreByManagerCode($managerCode);
        }catch(Exception $e){
            throw $e;
        }
    }

    public function assignManagerWithStore($validatedData)
    {

        try{

            $validData['manager_code']=$validatedData['manager_code'];
            foreach($validatedData['store_code'] as $value){
                $validData['store_code'] = $value;
                $this->managerStoreRepo->assignStoreWithManager($validData);
            }
            return true;

        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function deleteAssignedStore($managerCode)
    {
        try{
            $storeManagerDetail = $this->managerStoreRepo->findorfailAssignedStoreByManagerStoreCode($managerCode);

            if(!is_null($storeManagerDetail)){
              $deleteStatus =  $this->managerStoreRepo->deleteAssignedStore($storeManagerDetail);
              if($deleteStatus){
                  $validData['manager_code'] = $storeManagerDetail['manager_code'];
                  $validData['store_code'] = $storeManagerDetail['store_code'];
                  $validData['assigned_by'] = $storeManagerDetail['assigned_by'];
                  $validData['assigned_date'] = $storeManagerDetail['created_at'];
                  $validData['removed_date'] = Carbon::now();

                  $this->managerStoreHistoryRepo->store($validData);
              }

            }
            return true;

        }catch(Exception $exception){
            throw $exception;
        }
    }

}
