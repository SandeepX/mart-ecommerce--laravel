<?php


namespace App\Modules\SalesManager\Requests\ManagerSMI;

use Illuminate\Foundation\Http\FormRequest;

class ManagerSMIAllowEditUpdateRequest extends FormRequest
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
            'allow_edit' => 'required|boolean:0,1',
            'allow_edit_remarks' => ['nullable', 'string', 'min:5']
        ];
    }

}





