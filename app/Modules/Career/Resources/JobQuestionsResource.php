<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/17/2020
 * Time: 3:27 PM
 */

namespace App\Modules\Career\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class JobQuestionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'question_code' => $this->question_code,
            'question' => $this->question,
            'slug' => $this->slug,
        ];
    }

}