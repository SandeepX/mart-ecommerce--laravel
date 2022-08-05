<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/16/2020
 * Time: 3:07 PM
 */

namespace App\Modules\Store\Resources\Kyc;


use Illuminate\Http\Resources\Json\JsonResource;

class KycBankResource extends JsonResource
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

           // 'kyc_bank_detail_code' =>$this->kyc_bank_detail_code,
            'kyc_code' =>$this->kyc_code,
            'bank_code' =>$this->bank_code,
            'bank_name' =>$this->bank->bank_name,
            'bank_branch_name' =>$this->bank_branch_name,
            'bank_account_no' =>$this->bank_account_no,
            'bank_account_holder_name' =>$this->bank_account_holder_name,

        ];
    }
}
