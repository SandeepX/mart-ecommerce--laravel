<?php

namespace App\Modules\ManagerDiary\Repositories\Diary;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\ManagerDiary\Models\Diary\ManagerDiary;
use Exception;

class ManagerDiaryRepository extends RepositoryAbstract
{

    public function findByManagerDiaryByCode($managerDiaryCode,$with=[]){
     return  ManagerDiary::with($with)->where('manager_diary_code',$managerDiaryCode)->first();
    }
    public function findOrFailManagerDiaryByCode($managerDiaryCode,$with = []){
           $managerDiary =  $this->findByManagerDiaryByCode($managerDiaryCode,$with);
           if(!$managerDiary){
             throw new Exception('Manager diary Detail Not found');
           }
           return $managerDiary;
    }

    public function getAllManagerDiariesByManagerCode($managerCode,$paginateBy=10){
          return ManagerDiary::where('manager_code',$managerCode)->latest()->paginate($paginateBy);
    }

    public function save($validatedData){
         $managerDiary = ManagerDiary::create($validatedData);
         return $managerDiary->fresh();
    }

    public function update(ManagerDiary $managerDiary,$validatedData){
        $managerDiary->update($validatedData);
        return $managerDiary->refresh();
    }

}
