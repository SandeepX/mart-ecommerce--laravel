<?php

namespace App\Modules\ManagerDiary\Requests\VisitClaim;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RespondToVisitClaimRequest extends FormRequest
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
           'status' => ['required',Rule::in('verified','rejected')],
           'remarks' => ['required','string']
        ];
    }
}
