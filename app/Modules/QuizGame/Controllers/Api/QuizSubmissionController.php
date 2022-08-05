<?php


namespace App\Modules\QuizGame\Controllers\Api;

use App\Modules\QuizGame\Requests\QuizSubmission\QuizSubmissionStoreRequest;
use App\Modules\QuizGame\Resources\ParticipatorParticipatedQuiz\ParticipatorParticipatedAllQuizCollection;
use App\Modules\QuizGame\Resources\QuizOnlyPassageDetailResource;
use App\Modules\QuizGame\Resources\QuizSubmittedDetail\QuizSubmittedDetailCollection;
use App\Modules\QuizGame\Services\QuizQuestionService;
use App\Modules\QuizGame\Services\QuizService;
use App\Modules\QuizGame\Services\QuizSubmissionService;
use App\Modules\QuizGame\Services\QuizSubmittedDetailService;
use App\Modules\QuizGame\Transformers\QuizGameDetailTransformer;
use Illuminate\Support\Facades\DB;

class QuizSubmissionController
{
    public $quizSubmissionService;
    public $quizService;
    public $quizQuestionService;
    public $quizSubmittedDetailService;

    public function __construct(
                                QuizSubmissionService $quizSubmissionService,
                                QuizService $quizService,
                                QuizQuestionService $quizQuestionService,
                                QuizSubmittedDetailService $quizSubmittedDetailService
    )
    {
        $this->quizSubmissionService = $quizSubmissionService;
        $this->quizService = $quizService;
        $this->quizQuestionService = $quizQuestionService;
        $this->quizSubmittedDetailService = $quizSubmittedDetailService;
    }

    public function storeQuizSubmission(QuizSubmissionStoreRequest $request)
    {
        try{
            DB::beginTransaction();
            $validatedData = $request->validated();
            $quizPassage = $this->quizService->findPassageDetailOfTheDayByCode($validatedData['qp_code']);
            $checkAnswer = $this->quizSubmissionService->checkQuizSubmittedAnswer($validatedData['qp_code'],$validatedData['quiz']);
            if($checkAnswer && count($checkAnswer)>0){
                return sendSuccessResponse('Incorrect Answer Submission',[
                    'action' => 'incorrect-answer-submission',
                    'right_answers' => $checkAnswer
                ]);
            }
            $quizSubmission = $this->quizSubmissionService->storeQuizSubmission($validatedData,$quizPassage);
            $submittedPassageData = [
                'quiz_submitted_date' => $quizSubmission->submitted_date,
                'passage_detail' => new QuizOnlyPassageDetailResource($quizSubmission->quizPassage),
                'submitted_quiz_detail'=> new QuizSubmittedDetailCollection($quizSubmission->quizSubmissionDetail)
            ];
            DB::commit();
            return sendSuccessResponse('Quiz Submitted Successfully',$submittedPassageData);

        }catch (\Exception $exception){
            DB::rollBack();
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function getAllQuizSubmissionByParticipator()
    {
        try{
            $participator_code = (new QuizGameDetailTransformer())->getParticipatorCode();
            $with = ['quizPassage:qp_code,passage_title'];
            $select = ['qp_code','quiz_submission_code','submitted_date'];
            $quizDetail = $this->quizSubmissionService->getAllQuizDetailByParticipatorCode($participator_code,$select,$with);
            $quizDetail = new ParticipatorParticipatedAllQuizCollection($quizDetail);
            return sendSuccessResponse('Data Found',$quizDetail);
        }catch (\Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function getQuizSubmittedDetailByQSCode($qs_code)
    {
        try{
            $submittedQuizDetail = $this->quizSubmittedDetailService->getQuizSubmittedDetailByQSCode($qs_code);
            $passage = $submittedQuizDetail[0]->quizSubmission->quizPassage->passage;
            $submittedQuizDetail = [
                'passage' => $passage,
                'answerQuestionDetail' =>  new QuizSubmittedDetailCollection($submittedQuizDetail)
            ];
//            $submittedQuizDetail = new QuizSubmittedDetailCollection($submittedQuizDetail);
            return sendSuccessResponse('Data Found',$submittedQuizDetail);
        }catch (\Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }

    public function checkCorrectAnswerOfQQCode($questionCode,$submittedAnswer){
        try{
            $question = $this->quizQuestionService->findOrFailByQuestionCode($questionCode);
            $quizQuestionDetails = [
                'is_correct' => $question->correct_answer == $submittedAnswer ? true : false,
                'correct_answer' => $question->correct_answer
            ];
            return sendSuccessResponse('Data Found',$quizQuestionDetails);
        }catch (\Exception $exception){
            return sendErrorResponse($exception->getMessage(),$exception->getCode());
        }
    }


}
