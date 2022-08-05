<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/15/2020
 * Time: 1:02 PM
 */

namespace App\Modules\Career\Services;


use App\Modules\Career\Repositories\JobQuestionRepository;

use Exception;
use DB;

class JobQuestionService
{

    private $jobQuestionRepository;

    public function __construct(JobQuestionRepository $jobQuestionRepository){

        $this->jobQuestionRepository = $jobQuestionRepository;
    }

    public function getAllJobQuestions(){

        return $this->jobQuestionRepository->getAll();
    }

    public function getActiveJobQuestions(){

        return $this->jobQuestionRepository->getAll(true);
    }


    public function findOrFailJobQuestionByCode($code){

        return $this->jobQuestionRepository->findOrFailByCode($code);
    }

    public function saveJobQuestion($validatedData){

        try{
            $validatedData['is_active'] = isset($validatedData['status']) ? 1 : 0;
           // $validatedData['slug'] = make_slug($validatedData['question']);
            DB::beginTransaction();
          // dd($validatedData);
            $jobQuestion=$this->jobQuestionRepository->save($validatedData);
            DB::commit();

            return $jobQuestion;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function updateJobQuestion($validatedData,$questionCode){
        //dd($validatedData);
        try{
            $jobQuestion= $this->jobQuestionRepository->findOrFailByCode($questionCode);
            $validatedData['is_active'] = isset($validatedData['status']) ? 1 : 0;
            unset($validatedData['status']);//removing status key
           // $validatedData['slug'] = make_slug($validatedData['question']);
            DB::beginTransaction();
            $jobQuestion=$this->jobQuestionRepository->update($jobQuestion,$validatedData);
            DB::commit();

            return $jobQuestion;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    public function deleteJobQuestion($questionCode)
    {
        try {
            DB::beginTransaction();
            $jobQuestion = $this->jobQuestionRepository->findOrFailByCode($questionCode);
            $jobQuestion = $this->jobQuestionRepository->delete($jobQuestion);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return $jobQuestion;
    }
}