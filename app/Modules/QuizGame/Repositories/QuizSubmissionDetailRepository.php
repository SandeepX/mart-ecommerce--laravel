<?php


namespace App\Modules\QuizGame\Repositories;


use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\QuizGame\Models\QuizSubmissionDetail;


class QuizSubmissionDetailRepository extends RepositoryAbstract
{

    public function store($validatedData)
    {
        return QuizSubmissionDetail::create($validatedData)->fresh();
    }

    public function getQuizSubmittedDetailByQSCode($qs_code)
    {
        return QuizSubmissionDetail::where('quiz_submission_code',$qs_code)->get();
    }

}
