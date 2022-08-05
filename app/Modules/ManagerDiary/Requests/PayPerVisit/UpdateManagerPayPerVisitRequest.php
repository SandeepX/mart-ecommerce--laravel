<?php

namespace App\Modules\ManagerDiary\Requests\PayPerVisit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateManagerPayPerVisitRequest extends FormRequest
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
            'amount'=>['required','numeric','min:0']
        ];
    }

}
