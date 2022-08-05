<?php


namespace App\Modules\QuizGame\Controllers\Api;


use App\Modules\QuizGame\Requests\QuizParticipator\QuizParticipatorDetailStoreRequest;
use App\Modules\QuizGame\Requests\QuizParticipator\QuizParticipatorUpdateRequest;
use App\Modules\QuizGame\Services\QuizParticipatorService;
use Illuminate\Http\Request;

class QuizParticipatorController
{
    public $quizParticipatorService;

    public function __construct(QuizParticipatorService $quizParticipatorService)
    {
        $this->quizParticipatorService = $quizParticipatorService;
    }

    public function storeParticipatorDetail(QuizParticipatorDetailStoreRequest $request)
    {
        try{
            $validatedData = $request->validated();
            $quizParticipatorDetail = $this->quizParticipatorService
                ->storeQuizParticipatoreDetail($validatedData);

            return sendSuccessResponse('Data submitted successfully');
        }catch(\Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());

        }
    }

    public function updateParticipatorDetail(QuizParticipatorUpdateRequest $request,$qpd_code)
    {
        try{
            $validatedData = $request->validated();
            $quizParticipatorDetail = $this->quizParticipatorService->findQuizParticipatorDetailByCode($qpd_code);
            $updateStatus = $this->quizParticipatorService->updateParticipatorDetail($validatedData,$quizParticipatorDetail);
            return sendSuccessResponse('Data Updated successfully',$validatedData);
        }catch (\Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }






}
