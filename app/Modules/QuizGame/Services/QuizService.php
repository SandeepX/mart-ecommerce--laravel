<?php


namespace App\Modules\QuizGame\Services;

use App\Modules\QuizGame\Repositories\QuizDateRepository;
use App\Modules\QuizGame\Repositories\QuizPassageRepository;
use App\Modules\QuizGame\Repositories\QuizQuestionRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class QuizService
{
    private $quizDateRepo;
    private $quizPassageRepo;
    private $quizQuestionRepo;

    public function __construct(
        QuizDateRepository $quizDateRepo,
        QuizPassageRepository $quizPassageRepo,
        QuizQuestionRepository $quizQuestionRepo

    )
    {
        $this->quizDateRepo = $quizDateRepo;
        $this->quizPassageRepo = $quizPassageRepo;
        $this->quizQuestionRepo = $quizQuestionRepo;
    }

    public function getALlQuizPassages()
    {
        return $this->quizPassageRepo->getAllQuizPassages();
    }

    public function storeQuizPassageDetail($validatedPassageData,$validatedQuizDate,$validatedQuestion)
    {
        try{
          DB::beginTransaction();
            $quizDatesDetail = $this->quizDateRepo->getQuizDateDetailUsingParameterDate(
                $validatedQuizDate['quiz_passage_date']
            );
            if($quizDatesDetail->isNotEmpty()){
                throw new Exception('Passage for the date ' .$quizDatesDetail[0]['quiz_passage_date']. ' already scheduled',400);
            }
            //save passage data
            $quizPassage = $this->quizPassageRepo->store($validatedPassageData);

            if($quizPassage){
                //save quiz passage dates
              $validatedData['qp_code'] = $quizPassage->qp_code;
              foreach($validatedQuizDate['quiz_passage_date'] as $quizDateData){
                  $validatedData['quiz_passage_date'] = $quizDateData;
                  $this->quizDateRepo->store($validatedData);
              }
              //save passage question
              foreach($validatedQuestion['quiz'] as $key => $questionsData){
                  $questionsData['qp_code'] = $quizPassage->qp_code;
                  $this->quizQuestionRepo->store($questionsData);
              }
            }
            DB::commit();
            return $quizPassage;
        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function findPassageDetailByCode($qp_code,$with=[])
    {
        try{
            $quizPassageDetail = $this->quizPassageRepo
                ->with($with)
                ->findQuizPassageDetailByCode($qp_code);
            if(!$quizPassageDetail){
                throw new Exception('Passage Detail Not Found',404);
            }
            return $quizPassageDetail;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function findNotExpiredPassageDetailByPassageCode($qp_code,$with=[])
    {
        try{
            $quizPassageDetail = $this->quizPassageRepo
                ->with($with)
                ->findNotExpiredPassageDetailByPassageCode($qp_code);
            if(!$quizPassageDetail){
                throw new Exception('Cannot Add More Question, Passage Already Expired');
            }
            return $quizPassageDetail;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function togglePassageStatus($passageDetail)
    {
        try{
            DB::beginTransaction();
            $changePassageStatus = $this->quizPassageRepo->toggleStatus($passageDetail);
            DB::commit();
            return $changePassageStatus;
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function deletePassageDetailAlongWithQuestions($quizPassageDetail)
    {
        try{
            if(count($quizPassageDetail->quizSubmission)>0){
                throw new Exception("Can't delete passage");
            }
            DB::beginTransaction();
                $deleteStatus = $this->quizPassageRepo->delete($quizPassageDetail);
            DB::commit();
            return $deleteStatus;
        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function getPassageDetailOfTheDay()
    {
        try{
            $quizPassageOfTheDay = $this->quizPassageRepo->findPassageDetailOfTheDay();
            if(!$quizPassageOfTheDay){
                throw new Exception('Quiz Not found for the day '.Carbon::today()->format('Y-m-d'),404);
            }
            return $quizPassageOfTheDay;
        }catch(Exception $e){
            throw $e;
        }
    }

    public function findPassageDetailOfTheDayByCode($qp_code)
    {
        try{
            $quizPassage = $this->quizPassageRepo->findPassageDetailOfTheDay();
            if(!$quizPassage || ($quizPassage['qp_code'] !== $qp_code)){
                throw new Exception( 'Passage detail doesnot match with Quiz of the day:'.Carbon::today()->format('Y-m-d')
                 ,404);
            }
            return $quizPassage;
        }catch(Exception $e){
            throw $e;
        }
    }

    public function updateQuizPassageDetail($validatedPassageData,$validatedQuizDate,$qpd_code)
    {
        try{
            DB::beginTransaction();
            $quizPassageDetail = $this->findPassageDetailByCode($qpd_code);

            $quizDatesDetailBeforeUpdate  = $this->quizDateRepo->findPassageScheduledDatesByQPDCode($qpd_code);

            $this->quizDateRepo->deleteDateCollection($quizDatesDetailBeforeUpdate);

            $quizDatesDetail = $this->quizDateRepo->getQuizDateDetailUsingParameterDate(
                $validatedQuizDate['quiz_passage_date']
            );

            if($quizDatesDetail->isNotEmpty()){
                throw new Exception('Passage for the date ' .$quizDatesDetail[0]['quiz_passage_date']. ' already scheduled',400);
            }
            //update passage data
            $quizPassage = $this->quizPassageRepo->update($validatedPassageData,$quizPassageDetail);

            if($quizPassage){
                //update quiz passage dates
                $validatedData['qp_code'] = $qpd_code;
                foreach($validatedQuizDate['quiz_passage_date'] as $quizDateData){
                    $validatedData['quiz_passage_date'] = $quizDateData;
                    $this->quizDateRepo->store($validatedData);
                }
            }
            DB::commit();
            return $quizPassage;
        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

}
