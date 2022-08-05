<?php

namespace App\Modules\ManagerDiary\Requests\VisitClaim;

use Illuminate\Foundation\Http\FormRequest;

class ScanStoreVisitClainByStoreRequest extends FormRequest
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
            'store_latitude' => ['required','numeric'],
            'store_longitude' => ['required','numeric']
        ];
    }

}
