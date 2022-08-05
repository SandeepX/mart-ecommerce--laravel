<?php


namespace App\Modules\QuizGame\Repositories;


use App\Modules\QuizGame\Models\QuizDate;
use Carbon\Carbon;

class QuizDateRepository
{

    public function getQuizDateDetailUsingParameterDate($dates)
    {
        return QuizDate::whereIn('quiz_passage_date',$dates)->get();
    }

    public function store($validatedData)
    {
        return QuizDate::create($validatedData)->fresh();
    }

    public function findPassageScheduledDatesByQPDCode($qp_code)
    {
        return QuizDate::where('qp_code',$qp_code)
//            ->where('quiz_passage_date','>=',Carbon::today()->format('Y-m-d'))
            ->get();
    }

    public function deleteDateCollection($quizDatesDetail)
    {
        return $quizDatesDetail->each->delete();
    }

}
