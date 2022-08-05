<?php


namespace App\Modules\QuizGame\Services;


use App\Modules\QuizGame\Repositories\QuizQuestionRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class QuizQuestionService
{
    private $questionRepo;

    public function __construct(QuizQuestionRepository $questionRepo)
    {
        $this->questionRepo = $questionRepo;
    }

    public function findOrFailByQuestionCode($questionCode){
         return $this->questionRepo->findOrFailQuestionDetailByCode($questionCode);
    }

    public function findPassageQuestionDetailByCode($questionCode)
    {
        try{
            $questionDetail = $this->questionRepo->findQuestionDetailByCode($questionCode);
            if(!$questionDetail){
                throw new \Exception('Question Detail Not Found',404);
            }
            return $questionDetail;
        }catch(\Exception $e){
            throw $e;
        }
    }



    public function updateQuestion($validatedData, $quizQuestionDetail)
    {
        try{
            DB::beginTransaction();
            if(!isset($validatedData['question_is_active'])){
                $validatedData['question_is_active'] = 0;
            }
            $quizQuestion = $this->questionRepo->update($validatedData,$quizQuestionDetail);
            DB::commit();
            return $quizQuestion;
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function deletePassageQuestions($questionDetail)
    {
        try{
            if(count($questionDetail->quizSubmittedQuestion)>0){
                    throw new Exception("Can't delete question: ".ucfirst($questionDetail['question']));
            }
            DB::beginTransaction();
                $question = $this->questionRepo->delete($questionDetail);
            DB::commit();
            return $question;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function addMoreQuestionToPassage($passageDetail,$validatedData)
    {
        try{
            $quizQuestions = [];
            foreach($validatedData['quiz'] as $key => $value){
               $quizQuestions[$key]['question'] = $value['question'];
               $quizQuestions[$key]['option_a'] = $value['option_a'];
               $quizQuestions[$key]['option_b'] = $value['option_b'];
               $quizQuestions[$key]['option_c'] = $value['option_c'];
               $quizQuestions[$key]['option_d'] = $value['option_d'];
               $quizQuestions[$key]['correct_answer'] = $value['correct_answer'];
               $quizQuestions[$key]['points'] = $value['points'];
               $quizQuestions[$key]['question_is_active'] = isset($value['question_is_active'])?$value['question_is_active']:0;
            }
            DB::beginTransaction();
            $question = $this->questionRepo->createManyQuestionInPassage($passageDetail,$quizQuestions);
            DB::commit();
            return $question;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

}
