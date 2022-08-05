<?php


namespace App\Modules\QuizGame\Resources\ParticipatorParticipatedQuiz;


use Illuminate\Http\Resources\Json\JsonResource;

class ParticipatorParticipatedAllQuizResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'quiz_submission_code' => $this->quiz_submission_code,
            'quiz_title' => ucfirst($this->quizPassage->passage_title),
            'submitted_date' => $this->submitted_date,
        ];
    }
}







