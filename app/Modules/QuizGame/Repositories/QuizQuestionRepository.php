<?php


namespace App\Modules\QuizGame\Repositories;


use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\QuizGame\Models\QuizPassage;
use App\Modules\QuizGame\Models\QuizQuestion;
use Carbon\Carbon;

class QuizQuestionRepository extends RepositoryAbstract
{

    public function store($validatedDate)
    {
        return QuizQuestion::create($validatedDate)->fresh();
    }

    public function findOrFailQuestionDetailByCode($questionCode){
       $quizQuestion = $this->findQuestionDetailByCode($questionCode);
       if(!$quizQuestion){
         throw new \Exception('Question not found :)');
       }
       return $quizQuestion;
    }

    public function findQuestionDetailByCode($questionCode)
    {
        return QuizQuestion::where('question_code',$questionCode)
            ->select($this->select)
            ->first();
    }

    public function getQuestionsByQPCode($qp_code)
    {
        return QuizQuestion::where('qp_code',$qp_code)
            ->select($this->select)
            ->get();
    }

    public function getActiveQuestionByQPCode($qp_code)
    {
        return QuizQuestion::where('qp_code',$qp_code)
            ->where('question_is_active',1)
            ->select($this->select)
            ->get();
    }

    public function findIsActiveQuestionDetailByCode($questionCode)
    {
        return QuizQuestion::where('question_code',$questionCode)
            ->select($this->select)
            ->where('question_is_active',1)
            ->first();
    }

    public function update($validatedData, $quizQuestionDetail)
    {
        return $quizQuestionDetail->update($validatedData);
    }

    public function delete($questionDetail)
    {
        return $questionDetail->delete();
    }

    public function createManyQuestionInPassage(QuizPassage $passageDetail,$quizQuestions)
    {
        return $passageDetail->quizQuestions()->createMany($quizQuestions);
    }

}
