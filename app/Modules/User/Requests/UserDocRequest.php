<?php

namespace App\Modules\User\Requests;

use App\Modules\User\Models\UserDoc;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserDocRequest extends FormRequest
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
        $rules = [
            'doc_name' => ['nullable','array','min:1'],
            'doc_name.*' => ['nullable',Rule::in(UserDoc::MANAGER_DOC_TYPES)],
            //'doc_name.*' => ['nullable',Rule::in(User::MANAGER_DOC_TYPES)],
           # 'doc_name.*' => ['nullable','max:100'],
            'doc_number' => ['required','array','min:1'],
            'doc_number.*' => ['required','max:50'],
            'doc' => ['nullable','array','min:1'],
            'doc.*' => ['nullable','image','mimes:jpeg,png,jpg,pdf,docx','max:10240'],
//            'doc_issued_district' => ['nullable','array','min:1','exists:location_hierarchy,location_code'],
//            'doc_issued_district.*' => ['nullable','max:50'],
            'doc_issued_district' => 'nullable|exists:location_hierarchy,location_code'
        ];


        return $rules;
    }
}
