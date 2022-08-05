<?php


/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 06/12/2021
 * Time: 3:20 PM
 */

namespace App\Modules\QuizGame\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizPassageDetailResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'qp_code' => $this->qp_code,
            'passage_title' => $this->passage_title,
            'passage' => $this->passage,
            'date' => Carbon::today()->format('Y-m-d'),
            'questionDetail' => new QuizQuestionCollection($this->quizQuestions->where('question_is_active',1))
        ];
    }
}






