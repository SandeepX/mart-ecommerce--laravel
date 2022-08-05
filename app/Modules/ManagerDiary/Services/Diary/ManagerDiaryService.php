<?php

namespace App\Modules\ManagerDiary\Services\Diary;

use App\Modules\Location\Traits\LocationHelper;
use App\Modules\ManagerDiary\Repositories\Diary\ManagerDiaryRepository;
use Exception;
use function getAuthGuardUserCode;
use function getAuthManagerCode;

class ManagerDiaryService
{
    use LocationHelper;
    protected $managerDiaryRepository;

    public function __construct(ManagerDiaryRepository $managerDiaryRepository)
    {
        $this->managerDiaryRepository = $managerDiaryRepository;
    }

    public function getAllDiariesByManagerCode($managerCode,$paginateBy = 10){
        try{
          $managerDiaries =  $this->managerDiaryRepository->getAllManagerDiariesByManagerCode($managerCode,$paginateBy);
          return $managerDiaries;
        }catch (Exception $exception){
          throw $exception;
        }
    }

    public function findOrFailManagerDiaryByCode($managerDiaryCode,$with=[]){
        try{
            $managerDiary = $this->managerDiaryRepository->findOrFailManagerDiaryByCode($managerDiaryCode,$with);
            return $managerDiary;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function saveManagerDiaryDetails($validatedData){
        try{
            $validatedData['manager_code'] = getAuthManagerCode();
            $validatedData['created_by'] = getAuthGuardUserCode();
            $validatedData['updated_by'] = getAuthGuardUserCode();
            $managerDiary = $this->managerDiaryRepository->save($validatedData);
            $managerDiary = $this->managerDiaryRepository->update(
                $managerDiary ,
                ['full_location'=>$this->getFullLocationPathByLocation($managerDiary->ward)]
            );
            return $managerDiary;
        }catch (Exception $exception){
          throw $exception;
        }
    }

    public function updateManagerDiaryDetails($managerDiaryCode,$validatedData){
        try{
            $managerDiary = $this->managerDiaryRepository->findOrFailManagerDiaryByCode($managerDiaryCode);

            $updateFullLocation = ($managerDiary->ward_code != $validatedData['ward_code']) ? true : false;

            if($managerDiary->manager_code != getAuthManagerCode()){
              throw new Exception('The given manager diary is invalid!');
            }
            $validatedData['updated_by'] = getAuthGuardUserCode();
            $managerDiary = $this->managerDiaryRepository->update($managerDiary,$validatedData);

            if($updateFullLocation){
                $managerDiary = $this->managerDiaryRepository->update($managerDiary,
                    ['full_location'=>$this->getFullLocationPathByLocation($managerDiary->ward)]
                );
            }

            return $managerDiary;
        }catch (Exception $exception){
            throw $exception;
        }
    }


}
