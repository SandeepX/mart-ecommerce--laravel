<?php

namespace App\Modules\SalesManager\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class MinimalManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
   public function toArray( $request){
       $result = [
           'manager_name' => $this->manager_name,
           'manager_code' => $this->manager_code,
           'referral_code' => $this->referral_code,
       ];
       return $result;
   }
}
