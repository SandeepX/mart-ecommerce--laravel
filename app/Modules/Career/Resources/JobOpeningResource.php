<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/17/2020
 * Time: 3:17 PM
 */

namespace App\Modules\Career\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class JobOpeningResource extends JsonResource
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
            'title' => $this->title,//
            'slug' => $this->slug,//
            'location' => $this->location,//
            'job_type' => $this->job_type,//
            'display_job_type' => convertToWords('_',$this->job_type),

        ];
    }
}