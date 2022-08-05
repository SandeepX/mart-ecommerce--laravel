<?php


namespace App\Modules\QuizGame\Repositories;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\QuizGame\Models\QuizPassage;
use Carbon\Carbon;

class QuizPassageRepository extends RepositoryAbstract
{

    public function store($validatedDate)
    {
        return QuizPassage::create($validatedDate)->fresh();
    }

    public function getAllQuizPassages()
    {
        return QuizPassage::latest()->paginate(5);
    }

    public function findQuizPassageDetailByCode($qp_code)
    {
        return QuizPassage::with($this->with)
            ->where('qp_code',$qp_code)
            ->first();
    }

    public function toggleStatus($passageDetail)
    {
        return $passageDetail->update([
            'passage_is_active' => !$passageDetail['passage_is_active']
        ]);
    }

    public function delete($quizPassageDetail)
    {
        return $quizPassageDetail->delete();
    }

    public function findPassageDetailOfTheDay()
    {
        $quizPassage = QuizPassage::where('passage_is_active',1)
            ->whereHas('quizDates',function ($query){
                $query->where('quiz_passage_date',Carbon::today());
            })
            ->first();
        return $quizPassage;
    }

    public function update($validatedPassageData,$quizPassageDetail)
    {
        return $quizPassageDetail->update($validatedPassageData);
    }

    public function findNotExpiredPassageDetailByPassageCode($passageCode)
    {
        $quizPassage = QuizPassage::where('qp_code',$passageCode)
            ->whereHas('quizDates',function ($query){
                $query->where('quiz_passage_date','>=',Carbon::today());
            })
            ->first();
        return $quizPassage;
    }
}
