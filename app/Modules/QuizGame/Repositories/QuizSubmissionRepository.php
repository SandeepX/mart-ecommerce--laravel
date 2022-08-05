<?php


namespace App\Modules\QuizGame\Repositories;


use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\QuizGame\Models\QuizSubmission;
use Carbon\Carbon;

class QuizSubmissionRepository extends RepositoryAbstract
{

    public function getAllQuizDetailByParticipatorCode($participator_code)
    {
       return QuizSubmission::with($this->with)
           ->select($this->select)
           ->where('participator_code',$participator_code)
           ->latest()
           ->get();
    }

    public function getQuizSubmissionDetailByQPCode($qp_code)
    {
        return QuizSubmission::where('qp_code',$qp_code)
            ->where('submitted_date',Carbon::today())
            ->get();
    }

    public function getSubmissionDetailByParticipatorAndQPCode($participator_code,$qp_code)
    {
        return QuizSubmission::where('participator_code',$participator_code)
            ->where('qp_code',$qp_code)
            ->where('submitted_date',Carbon::today()->format('Y-m-d'))
            ->get();
    }

    public function store($validatedData)
    {
        return QuizSubmission::create($validatedData)->fresh();
    }

    public function createManyQuizSubmissionDetail(QuizSubmission $quizSubmission,$quizSubmissionDetailData)
    {
        return $quizSubmission->quizSubmissionDetail()->createMany($quizSubmissionDetailData);
    }

}
