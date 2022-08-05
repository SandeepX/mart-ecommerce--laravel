<?php


namespace App\Modules\SalesManager\Resources\SalesManagerWallet;


use Illuminate\Http\Resources\Json\JsonResource;

class SalesManagerWalletTransactionResource extends JsonResource
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
            'manager_name '=>$this->wallet->walletable->manager_name,
            'transaction_amount'=>$this->amount,
            'transaction_type'=>  $this->purpose,
            'remarks'=> isset($this->remarks) ? $this->remarks : '',
            'accounting_entry_type' => $this->accounting_entry_type,
            'current_balance'=>$this->balance,
            'verified_by'=> ($this->createdBy) ? $this->createdBy->name : '',
            'verified_at'=> $this->created_at,
        ];
    }


}
