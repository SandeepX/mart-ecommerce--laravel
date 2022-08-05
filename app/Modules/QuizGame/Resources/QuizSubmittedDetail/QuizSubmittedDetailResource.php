<?php


namespace App\Modules\QuizGame\Resources\QuizSubmittedDetail;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizSubmittedDetailResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'qsd_code' => $this->qsd_code,
            'quiz_submission_code' => $this->quiz_submission_code,
            'question_code' => $this->question_code,
            'question' => ucfirst($this->question),
            'correct_option' => $this->correct_option,
            'answer' => ucfirst($this->answer),
        ];
    }
}








