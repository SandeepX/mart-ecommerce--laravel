<?php


namespace App\Modules\SalesManager\Requests\ManagerSMI;

use App\Modules\SalesManager\Models\ManagerSMI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ManagerSMIUpdateStatusRequest extends FormRequest
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
            'status' => ['required','string',Rule::in('approved','rejected')],
            'remarks' => ['nullable','string','max:1000']
        ];
    }

}




