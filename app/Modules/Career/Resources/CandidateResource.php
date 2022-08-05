<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/17/2020
 * Time: 3:17 PM
 */

namespace App\Modules\Career\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
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
            'career_id'=>$this->career_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'gender' => $this->job_type,
            'cover_letter'=>$this->cover_letter,
            'cv_file'=>$this->cv_file,


        ];
    }
}
