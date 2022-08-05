<?php


namespace App\Modules\QuizGame\Repositories;


use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\QuizGame\Models\QuizParticipator;

class QuizParticipatorRepository extends RepositoryAbstract
{

    public function getAllQuizParticipator()
    {
        return QuizParticipator::latest()->paginate(10);
    }


    public function findQuizParticipatorDetailByCode($qpd_code)
    {
        return QuizParticipator::where('qpd_code',$qpd_code)->first();
    }

    public function store($validatedData)
    {
        return QuizParticipator::create($validatedData)->fresh();
    }

    public function update($validatedData,$quizParticipatorDetail)
    {
        return $quizParticipatorDetail->update($validatedData);
    }

    public function findQuizParticipatorDetailByParticipatorCode($participator_code)
    {
        return QuizParticipator::where('participator_code',$participator_code)
            ->first();
    }

    public function delete($participatorDetail)
    {
        return $participatorDetail->delete();
    }

}
