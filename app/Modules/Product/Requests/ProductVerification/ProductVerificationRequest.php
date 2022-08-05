<?php

namespace App\Modules\Product\Requests\ProductVerification;

use App\Modules\Product\Models\ProductMaster;
use Illuminate\Foundation\Http\FormRequest;

class ProductVerificationRequest extends FormRequest
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
            'verification_status' => 'required|in:approved,rejected,on hold',
            'remarks' => 'required',
        ];
    }
}
