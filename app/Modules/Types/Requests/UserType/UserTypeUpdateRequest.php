<?php

namespace App\Modules\Types\Requests\UserType;

use Illuminate\Foundation\Http\FormRequest;

class UserTypeUpdateRequest extends FormRequest
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
            'user_type_name' => 'required|max:50|unique:user_types,user_type_name,'.$this->route('user_type').',user_type_code',
            'remarks' => 'nullable|max:40',
        ];
    }
}
