<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/1/2020
 * Time: 5:25 PM
 */

namespace App\Modules\Store\Resources\StorePayment;


use Illuminate\Http\Resources\Json\JsonResource;

class StoreOrderOfflinePaymentMetaResource extends JsonResource
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

            'key' =>convertToWords($this->key,'_'),
            'value' =>$this->value,
        ];
    }
}