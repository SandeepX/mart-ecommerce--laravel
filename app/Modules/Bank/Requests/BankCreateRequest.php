<?php

namespace App\Modules\Bank\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankCreateRequest extends FormRequest
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
            'bank_name' => 'required|max:50|unique:banks,bank_name',
            // 'bank_code' => 'required|max:12|unique:banks,bank_code',
            'bank_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'remarks' => 'nullable|max:50',
        ];
    }
}
