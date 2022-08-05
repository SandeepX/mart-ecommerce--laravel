<?php

namespace App\Modules\ManagerDiary\Requests\VisitClaim;

use Illuminate\Foundation\Http\FormRequest;

class CreateStoreVisitClaimByManagerRequest extends FormRequest
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
            'manager_latitude' => ['required','numeric'],
            'manager_longitude' => ['required','numeric']
        ];
    }

}
