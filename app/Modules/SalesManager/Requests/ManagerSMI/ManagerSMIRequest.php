<?php


namespace App\Modules\SalesManager\Requests\ManagerSMI;

use Illuminate\Foundation\Http\FormRequest;

class ManagerSMIRequest extends FormRequest
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
            'manager_code' => 'required|string|exists:managers_detail,manager_code'
        ];
    }

}



