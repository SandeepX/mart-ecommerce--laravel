<?php


namespace App\Modules\Wallet\Resources;


use App\Modules\Wallet\Models\WalletTransactionPurpose;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletTransactionPurposeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data =  [
            'wallet_transaction_purpose_code' => $this->wallet_transaction_purpose_code,
            'purpose' => $this->purpose,
            'purpose_type' => $this->purpose_type,
            'slug' => $this->slug,
            'is_active' => $this->is_active,
            'required_fields' => isset(WalletTransactionPurpose::PURPOSE_WISE_REQUIRED_FIELDS[$this->slug])
                ? WalletTransactionPurpose::PURPOSE_WISE_REQUIRED_FIELDS[$this->slug]
                : []
        ];

        return $data;
    }

}
