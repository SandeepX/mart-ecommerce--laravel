<?php

namespace App\Modules\Types\Requests\RejectionParam;

use Illuminate\Foundation\Http\FormRequest;

class RejectionParamCreateRequest extends FormRequest
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
            'rejection_name' => 'required|max:50|unique:rejection_para,rejection_name',
            'remarks' => 'nullable|max:40',
        ];
    }
}
