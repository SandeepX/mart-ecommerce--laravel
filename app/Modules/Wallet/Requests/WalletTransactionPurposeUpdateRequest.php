<?php


namespace App\Modules\Wallet\Requests;

use App\Modules\Wallet\Models\WalletTransactionPurpose;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WalletTransactionPurposeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'purpose' => 'required|max:100',
            'purpose_type' => ['required',Rule::in(WalletTransactionPurpose::PURPOSE_TYPES)],
            'user_type_code' => ['required',
                Rule::exists('user_types','user_type_code')
                    ->whereNull('deleted_at')
            ],
            'is_active' => ['required',Rule::in(1,0)],
            'admin_control' => ['required',Rule::in(1,0)],
            'close_for_modification' => ['required',Rule::in(1,0)]
        ];
    }

}
