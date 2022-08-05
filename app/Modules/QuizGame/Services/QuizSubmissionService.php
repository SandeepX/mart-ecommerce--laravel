<?php


namespace App\Modules\QuizGame\Services;


use App\Modules\QuizGame\Models\QuizSubmissionDetail;
use App\Modules\QuizGame\Repositories\QuizQuestionRepository;
use App\Modules\QuizGame\Repositories\QuizSubmissionDetailRepository;
use App\Modules\QuizGame\Repositories\QuizSubmissionRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class QuizSubmissionService
{

    private $quizSubmissionRepo;
    private $quizQuestionRepo;
    private $quizSubmissionDetailRepo;

    public function __construct(QuizSubmissionRepository $quizSubmissionRepo,
                                QuizQuestionRepository $quizQuestionRepo,
                                QuizSubmissionDetailRepository $quizSubmissionDetailRepo
    )
    {
        $this->quizSubmissionRepo = $quizSubmissionRepo;
        $this->quizQuestionRepo = $quizQuestionRepo;
        $this->quizSubmissionDetailRepo = $quizSubmissionDetailRepo;
    }

    public function getAllQuizDetailByParticipatorCode($participatorCode,$select=[],$with=[])
    {
        try{
            $quizDetail = $this->quizSubmissionRepo
                ->with($with)
                ->select($select)
                ->getAllQuizDetailByParticipatorCode($participatorCode);
            if(!$quizDetail){
                throw new Exception('Quiz Submitted Not Found',404);
            }
            return $quizDetail;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function storeQuizSubmission($validatedData,$quizPassage)
    {
        try{
            if((count($validatedData['quiz']) !== count($quizPassage->isActiveQuizQuestion))){
                throw new Exception('Question count error',400);
            }
            $quizSubmission = $this->quizSubmissionRepo->getSubmissionDetailByParticipatorAndQPCode(
                    $validatedData['participator_code'],$validatedData['qp_code']);
            if($quizSubmission && count($quizSubmission)>0){
                throw new Exception('Quiz already played');
            }
            DB::beginTransaction();
                $quizSubmissionData['qp_code'] = $validatedData['qp_code'];
                $quizSubmissionData['participator_type'] = $validatedData['participator_type'];
                $quizSubmissionData['participator_code'] = $validatedData['participator_code'];
                $quizSubmission = $this->quizSubmissionRepo->store($quizSubmissionData);

                $quizSubmissionDetail = $this->storeQuizSubmissionDetail(
                    $quizSubmission,
                    $quizSubmission['quiz_submission_code'],
                    $quizSubmission['qp_code']
                );
            DB::commit();
            return $quizSubmission;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function checkQuizSubmittedAnswer($qp_code,$quizDetail)
    {
        try{
            $answerSubmitted = [];
            $correctAnswer = [];
            $select = ['correct_answer','question_code'];
            $quizQuestionDetail = $this->quizQuestionRepo->select($select)
                ->getActiveQuestionByQPCode($qp_code);
            foreach($quizQuestionDetail as $key =>$value){
                $correctAnswer[$value['question_code']] = $value['correct_answer'];
            }
            foreach($quizDetail as $key => $value){
                $answerSubmitted[$value['question_code']] = $value['answer'];
            }
            ksort($answerSubmitted);
            ksort($correctAnswer);
            return array_diff_assoc($correctAnswer,$answerSubmitted);
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function storeQuizSubmissionDetail($quizSubmission,$qs_code,$qp_code)
    {

        try{
            DB::beginTransaction();

            $select = ['question_code','question','correct_answer','option_a','option_b','option_c','option_d'];

            $getAnswerDetail = $this->quizQuestionRepo->select($select)->getQuestionsByQPCode($qp_code);

            $quizSubmissionDetailData = [];

            foreach($getAnswerDetail as $key =>$value){
                $quizSubmissionDetailData[$key]['quiz_submission_code'] = $qs_code;
                $quizSubmissionDetailData[$key]['question_code'] = $value['question_code'];
                $quizSubmissionDetailData[$key]['question'] = $value['question'];
                $quizSubmissionDetailData[$key]['correct_option'] = $value['correct_answer'];
                $quizSubmissionDetailData[$key]['answer'] = $value[$value['correct_answer']];
                $quizSubmissionDetailData[$key]['quiz_submission_code'] = $qs_code;
            }
            $quizSubmissionDetail = $this->quizSubmissionRepo
                ->createManyQuizSubmissionDetail($quizSubmission,$quizSubmissionDetailData);
            DB::commit();
            return $quizSubmissionDetail;
        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

}
