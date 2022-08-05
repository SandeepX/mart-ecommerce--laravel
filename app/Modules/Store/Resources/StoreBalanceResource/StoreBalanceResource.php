<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/1/2020
 * Time: 5:20 PM
 */

namespace App\Modules\Store\Resources\StoreBalanceResource;

use App\Modules\Store\Models\Payments\StoreBalanceMaster;
use App\Modules\Store\Requests\BalanceManagement\BalanceWithdrawRequest;


use Illuminate\Http\Resources\Json\JsonResource;

class StoreBalanceResource extends JsonResource
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
            'store_name '=>$this->wallet->walletable->store_name,
            'transaction_amount'=>$this->amount,
            'transaction_type'=>  $this->purpose,
            'remarks'=> isset($this->remarks) ? $this->remarks : '',
            'accounting_entry_type' => $this->accounting_entry_type,
            'current_balance'=>$this->balance,
            'verified_by'=>($this->createdBy) ? $this->createdBy->name : '',
            'verified_at'=> $this->created_at,
        ];
    }

}
