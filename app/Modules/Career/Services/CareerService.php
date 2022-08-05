<?php

namespace App\Modules\Career\Services;


use App\Modules\Career\Repositories\CareerRepository;
use Illuminate\Support\Facades\DB;

class CareerService
{
    private $careerRepository;

    public function __construct(CareerRepository $careerRepository){
        $this->careerRepository=$careerRepository;
    }

    public function createCareer($validateData){
        try{
            DB::beginTransaction();
            $this->careerRepository->createCareer($validateData);
            DB::commit();
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function getAllCareer(){
        try{
            return $this->careerRepository->getAllCareer();
        }catch(\Exception $exception){
            return $exception;
        }
    }
    public function getCareerByCode($career_code){
        try{
            return $this->careerRepository->findOrFailByCode($career_code);
        }catch(\Exception $exception){

        }
    }

    public function careerServiceUpdate($validatedData,$careerCode){
        try{
            DB::beginTransaction();
            $career=$this->getCareerByCode($careerCode);
            $this->careerRepository->updateCareer($validatedData,$career);
            DB::commit();
        }
        catch(\Exception $exception){
            DB::rollBack();
            return $exception;
        }
    }
    public function deleteCareer($careerSlug){
        try{
            DB::beginTransaction();
            $career=$this->careerRepository->findOrFailByCode($careerSlug);

            $career =$this->careerRepository->deleteCareer($career);
            DB::commit();
            return $career;
        }
        catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }
}
