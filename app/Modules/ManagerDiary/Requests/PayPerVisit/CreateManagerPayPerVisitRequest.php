<?php

namespace App\Modules\ManagerDiary\Requests\PayPerVisit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateManagerPayPerVisitRequest extends FormRequest
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

    public function rules()
    {
        return [
            'manager_code' => [
                'required',Rule::exists('managers_detail','manager_code'),
                Rule::unique('manager_pay_per_visits','manager_code'),
            ],
            'amount'=>['required','numeric','min:0']
        ];
    }

    public function messages()
    {
        return [
            'manager_code.exists' => 'Selected Manager is invalid',
            'manager_code.unique' => 'Selected Manager pay per visit already exists',
        ];
    }

}
