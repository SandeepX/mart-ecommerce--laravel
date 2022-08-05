<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/16/2020
 * Time: 4:22 PM
 */

namespace App\Modules\Store\Resources\Kyc;


use Illuminate\Http\Resources\Json\JsonResource;

class IndividualKycFamilyDetailResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd(count(json_decode($this->sons)));

        return [
            'spouse_name'=>$this->spouse_name,
            'father_name'=>$this->father_name,
            'mother_name'=>$this->mother_name,
            'grand_father_name'=>$this->grand_father_name,
            'grand_mother_name'=>$this->grand_mother_name,
            'sons'=>json_decode($this->sons),
           // 'sons'=>[1,2,4],
            'daughters'=>json_decode($this->daughters),
            'daughter_in_laws'=>json_decode($this->daughter_in_laws),
            'father_in_law'=>$this->father_in_law,
            'mother_in_law'=>$this->mother_in_law
        ];
    }
}