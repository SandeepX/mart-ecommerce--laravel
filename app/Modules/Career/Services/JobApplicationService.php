<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/18/2020
 * Time: 12:37 PM
 */

namespace App\Modules\Career\Services;


use App\Modules\Career\Models\JobOpening;
use App\Modules\Career\Repositories\JobApplicationRepository;

use Exception;
use DB;

class JobApplicationService
{

    private $jobApplicationRepository;

    public function __construct(JobApplicationRepository $jobApplicationRepository){

        $this->jobApplicationRepository = $jobApplicationRepository;
    }

    public function getAllJobApplications(){

        return $this->jobApplicationRepository->getAll();
    }

    public function getAllJobApplicationsWith(array $with){

        return $this->jobApplicationRepository->getAllWith($with);
    }

    public function getAllJobApplicationsEager(){

        $jobApplications = $this->jobApplicationRepository->getAllWith(['jobOpening','applicationDocuments'
            ,'answers','tempLocation','permanentLocation']);

        return $jobApplications;
    }

    public function findOrFailJobApplicationByCode($code){

        return $this->jobApplicationRepository->findOrFailByCode($code);
    }

    public function findOrFailJobApplicationByCodeWithEager($code){

        return $this->jobApplicationRepository->findOrFailByCodeWith($code,['jobOpening','applicationDocuments'
            ,'answers','answers.jobQuestion','tempLocation','permanentLocation']);
    }

    public function saveJobApplication(JobOpening $jobOpening,$validatedData){

        try{
            $jobOpeningQuestionsCode =$jobOpening->jobQuestions->pluck('question_code')->toArray();

            DB::beginTransaction();

            $jobApplication = $this->jobApplicationRepository->save($jobOpening,$validatedData);

            $this->jobApplicationRepository->saveJobApplicationDocument($jobApplication,$validatedData['cv'],'cv');

            if (isset($validatedData['job_answers'])){
                $inputQuestionsCode =array_keys($validatedData['job_answers']);
               // dd($jobOpeningQuestionsCode);
                //dd($inputQuestionsCode);
                if (!areArraysValueEqual($jobOpeningQuestionsCode,$inputQuestionsCode)){
                   throw new Exception('Trying to be smart huh! re-enter data again');
                }

                $toSaveAnswers =$this->getFormattedArrayJobApplicationWithAnswer($validatedData);

                $this->jobApplicationRepository->saveJobApplicationAnswers($jobApplication,$toSaveAnswers);

            }

            DB::commit();
            return $jobApplication;

        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function getFormattedArrayJobApplicationWithAnswer($validatedData){

        //returns with relation attach/sync array format laravel official
        $questionsCode = array_filter($validatedData['job_questions']);
        $answers = array_filter($validatedData['job_answers']);
        $toSaveAnswers=[];

        foreach ($questionsCode as $index=>$questionCode){
            $toSaveAnswers[$questionCode] =[
                'question_code'=>$questionCode,
                'answer'=>$answers[$questionCode]
            ];
        }

        return $toSaveAnswers;

    }


}