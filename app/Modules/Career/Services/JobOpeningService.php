<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/16/2020
 * Time: 4:32 PM
 */

namespace App\Modules\Career\Services;


use App\Modules\Career\Repositories\JobOpeningRepository;
use Exception;
use DB;

class JobOpeningService
{

    private $jobOpeningRepository;

    public function __construct(JobOpeningRepository $jobOpeningRepository){
        $this->jobOpeningRepository = $jobOpeningRepository;
    }

    public function getAllJobTypes(){

        return $this->jobOpeningRepository->getJobTypes();
    }

    public static function getAllJobTypesValue(){

        return JobOpeningRepository::getJobTypesValue();
    }

    public function getAllJobOpenings(){

        return $this->jobOpeningRepository->getAll();
    }

    public function getActiveJobOpenings(){

        return $this->jobOpeningRepository->getAll(true);
    }

    public function getActiveJobOpeningsWith(array $with){

        return $this->jobOpeningRepository->getAllWith(true,$with);
    }

    public function getActiveJobQuestions(){

        return $this->jobOpeningRepository->getAll(true);
    }

    public function findOrFailJobOpeningByCode($code){

        return $this->jobOpeningRepository->findOrFailByCode($code);
    }

    public function findOrFailJobOpeningByCodeWithEager($code){

        return $this->jobOpeningRepository->findOrFailByCodeWith($code,['jobQuestions']);
    }

    public function findOrFailJobOpeningBySlug($slug){

        return $this->jobOpeningRepository->findOrFailBySlug($slug);
    }


    public static function findOrFailJobOpeningBySlugWith($slug,array $with){

        return JobOpeningRepository::findOrFailBySlugWith($slug,$with);
    }

    public function saveJobOpening($validatedData){

        try{
            DB::beginTransaction();

            $validatedData['is_active'] = isset($validatedData['is_active']) ? 1 : 0;

            if (isset($validatedData['job_question_code'])){
                $toSaveQuestions= $this->getFormattedArrayJobQuestionWithPriority($validatedData);
                $jobOpening =$this->jobOpeningRepository->saveWithJobQuestion($validatedData,$toSaveQuestions);
            }
            else{
                $jobOpening =$this->jobOpeningRepository->save($validatedData);
            }


            DB::commit();
            return $jobOpening;
        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function updateJobOpening($validatedData,$jobOpeningCode){

        try{
            $jobOpening= $this->jobOpeningRepository->findOrFailByCode($jobOpeningCode);

            DB::beginTransaction();

            $validatedData['is_active'] = isset($validatedData['is_active']) ? 1 : 0;

            if (isset($validatedData['job_question_code'])){
                $toSaveQuestions= $this->getFormattedArrayJobQuestionWithPriority($validatedData);

                $jobOpening =$this->jobOpeningRepository->updateWithJobQuestion($jobOpening,$validatedData,$toSaveQuestions);
            }
            else{
                $jobOpening =$this->jobOpeningRepository->update($jobOpening,$validatedData);
            }


            DB::commit();
            return $jobOpening;
        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    private function getFormattedArrayJobQuestionWithPriority($validatedData){

        //returns with relation attach/sync array format laravel official
        $questions = array_filter($validatedData['job_question_code']);
        $priorities = array_filter($validatedData['question_priority']);
        $priorities = array_map('intval', $priorities);
        $toSaveQuestions=[];

        foreach ($questions as $index=>$question){
            $toSaveQuestions[$question] =[
                'priority'=>$priorities[$index]
            ];
        }

        return $toSaveQuestions;
    }

    public function deleteJobOpening($jobOpeningCode)
    {
        try {
            DB::beginTransaction();
            $jobOpening = $this->jobOpeningRepository->findOrFailByCode($jobOpeningCode);
            $jobOpening = $this->jobQuestionRepository->delete($jobOpening);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $jobOpening;
    }
}