<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/16/2020
 * Time: 4:29 PM
 */

namespace App\Modules\Store\Resources\Kyc;


use Illuminate\Http\Resources\Json\JsonResource;

class IndividualKycCitizenshipDetailResource extends JsonResource
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
            //'kyc_c_d_code'=>$this->kyc_c_d_code,
          //  'kyc_code'=>$this->kyc_code,

            'citizenship_no'=>$this->citizenship_no,
            'citizenship_nationality'=>$this->citizenship_nationality,
            'citizenship_nationality_display_as'=>ucfirst($this->citizenship_nationality),
            'citizenship_issued_date'=>$this->citizenship_issued_date,
            'citizenship_gender'=>$this->citizenship_gender,
            'citizenship_gender_display_as'=>config('kyc_information_transformation.gender')[$this->citizenship_gender],
            'citizenship_birth_place'=>$this->citizenship_birth_place,
            'citizenship_district'=>$this->citizenship_district,
            'citizenship_dob'=>$this->citizenship_dob,
            'citizenship_father_name'=>$this->citizenship_father_name,
           // 'citizenship_father_nationality'=>$this->citizenship_father_nationality,
            'citizenship_mother_name'=>$this->citizenship_mother_name,
           // 'citizenship_mother_nationality'=>$this->citizenship_mother_nationality,
            'citizenship_grandfather_name'=>$this->citizenship_grandfather_name,
            //'citizenship_grandfather_nationality'=>$this->citizenship_grandfather_nationality,

            'citizenship_spouse_name'=>$this->citizenship_spouse_name,
            //'citizenship_spouse_nationality'=>$this->citizenship_spouse_nationality

//            'citizenship_full_name'=>$this->citizenship_full_name,
//            'citizenship_ward_no'=>$this->citizenship_ward_no,
//            'citizenship_municipality'=>$this->citizenship_municipality,
//            'citizenship_father_address'=>$this->citizenship_father_address,
//            'citizenship_mother_address'=>$this->citizenship_mother_address,
//            'citizenship_spouse_address'=>$this->citizenship_spouse_address,
        ];

    }
}
