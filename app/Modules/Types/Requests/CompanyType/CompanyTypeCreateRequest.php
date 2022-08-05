<?php

namespace App\Modules\Types\Requests\CompanyType;

use Illuminate\Foundation\Http\FormRequest;

class CompanyTypeCreateRequest extends FormRequest
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
            'company_type_name' => 'required|max:50|unique:company_types,company_type_name',
            'remarks' => 'nullable|max:40',
        ];
    }
}
