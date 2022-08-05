<?php

namespace App\Modules\Types\Requests\RegistrationType;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationTypeUpdateRequest extends FormRequest
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
            'registration_type_name' => 'required|max:50|unique:registration_types,registration_type_name,'.$this->route('registration_type').',registration_type_code',
            'remarks' => 'nullable|max:40',
        ];
    }
}
