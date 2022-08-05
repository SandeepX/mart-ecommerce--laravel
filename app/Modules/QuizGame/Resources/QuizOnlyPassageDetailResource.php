<?php


namespace App\Modules\QuizGame\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuizOnlyPassageDetailResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'qp_code' => $this->qp_code,
            'passage_title' => ucfirst($this->passage_title),
            'passage' => ucfirst($this->passage),
        ];
    }
}







