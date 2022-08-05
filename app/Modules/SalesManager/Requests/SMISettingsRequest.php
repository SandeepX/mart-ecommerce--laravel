<?php


namespace App\Modules\SalesManager\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SMISettingsRequest extends FormRequest
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
            'salary' => ['required', 'numeric','gt:0'],
            'terms_and_condition' => ['required', 'string', 'min:10']
        ];
    }

}


