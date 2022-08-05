<?php


namespace App\Modules\QuizGame\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizPassageQuestionResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'question_code' => $this->question_code,
            'question' => ucfirst($this->question),
            'options' => [
                'option_a' => $this->option_a,
                'option_b' => $this->option_b,
                'option_c' => $this->option_c,
                'option_d' => $this->option_d
             ],
            //'correct_answer' => $this->correct_answer,
            'points' => $this->points
        ];
    }
}







