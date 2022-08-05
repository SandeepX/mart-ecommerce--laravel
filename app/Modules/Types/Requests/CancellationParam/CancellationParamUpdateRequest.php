<?php

namespace App\Modules\Types\Requests\CancellationParam;

use Illuminate\Foundation\Http\FormRequest;

class CancellationParamUpdateRequest extends FormRequest
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
            'cancellation_name' => 'required|max:50|unique:cancellation_para,cancellation_name,'.$this->route('cancellation_param').',cancellation_code',
            'remarks' => 'nullable|max:40',
        ];
    }
}
