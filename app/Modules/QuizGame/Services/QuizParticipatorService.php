<?php


namespace App\Modules\QuizGame\Services;


use App\Modules\QuizGame\Repositories\QuizParticipatorRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class QuizParticipatorService
{
    private $quizParticipatorRepo;

    public function __construct(QuizParticipatorRepository $quizParticipatorRepo)
    {
        $this->quizParticipatorRepo = $quizParticipatorRepo;
    }

    public function getALlQuizParticipator()
    {
        return $this->quizParticipatorRepo->getAllQuizParticipator();
    }

    public function findQuizParticipatorDetailByCode($qpd_code)
    {
        try{
            $quizParticipatorDetail = $this->quizParticipatorRepo->findQuizParticipatorDetailByCode($qpd_code);
            if(!$quizParticipatorDetail){
                throw new Exception('Quiz Participator Detail Not Found',404);
            }
            return $quizParticipatorDetail;
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function storeQuizParticipatoreDetail($validatedData)
    {
       try{
           DB::beginTransaction();
               $quizParticipatorDetail = $this->quizParticipatorRepo
                   ->store($validatedData);
           DB::commit();
           return $quizParticipatorDetail;
       }catch (\Exception $e){
           DB::rollBack();
           throw $e;
       }
    }

    public function updateParticipatorDetail($validatedData,$quizParticipatorDetail)
    {
        try{
            if($quizParticipatorDetail['status'] !== 'rejected'){
                throw new Exception(
                    'cannot update detail while status is in '.$quizParticipatorDetail['status']. ' state',
                    402);
            }
            $validatedData['status'] = 'pending';
            DB::beginTransaction();
                $quizParticipator = $this->quizParticipatorRepo
                    ->update($validatedData,$quizParticipatorDetail);
            DB::commit();
            return $quizParticipator;
        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function changeStatus($validatedData,$participatorDetail)
    {
        try{
            if($validatedData['status'] == 'pending'){
                throw new Exception('cannot update status to pending state',402);
            }
            if($participatorDetail['status'] != 'pending'){
                throw new Exception(
                    'Cannot update status ,Participator is already ' .ucfirst($participatorDetail['status']),
                    402);
            }
            $validatedData['status_reponded_at'] = Carbon::today()->format('Y-m-d');

            DB::beginTransaction();
            $quizParticipator = $this->quizParticipatorRepo
                ->update($validatedData,$participatorDetail);
            DB::commit();
            return $quizParticipator;
        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteParticipatorDetial($participatorDetail)
    {
        try{
            DB::beginTransaction();
            $quizParticipator = $this->quizParticipatorRepo->delete($participatorDetail);
            DB::commit();
            return $quizParticipator;
        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }
    }


}
