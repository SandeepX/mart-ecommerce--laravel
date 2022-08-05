<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/17/2020
 * Time: 5:30 PM
 */

namespace App\Modules\Career\Resources;


use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class JobOpeningSingleResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'opening_code' => $this->opening_code,
            'title' => $this->title,
            'slug' => $this->slug,
            'location' => $this->location,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'salary' =>$this->salary? number_format($this->salary) : 'N/A',
            'job_type' => $this->job_type,
            'display_job_type' => convertToWords('_',$this->job_type),
            'job_questions' =>JobQuestionsResource::collection($this->jobQuestions()->orderBy('job_opening_question.priority','asc')->get()),
            'updated_at' => Carbon::parse($this->updated_at)->diffForHumans(),
        ];
    }
}