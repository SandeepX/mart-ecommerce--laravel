<?php

namespace App\Modules\OfflinePayment\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfflinePaymentRemarkCreateRequest extends FormRequest
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

        $rules = [
            'remark' => ['required','string','max:2000'],
        ];

        return $rules;
    }

}
