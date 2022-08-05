<?php


namespace App\Modules\SalesManager\Requests\ManagerProfileApiRequest;

use App\Modules\User\Models\UserDoc;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalesManagerUpdateDocsRequest extends FormRequest
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
            'doc_name' => ['nullable', 'array', 'min:1'],
            'doc_name.*' => ['nullable', Rule::in(UserDoc::MANAGER_DOC_TYPES)],
            'doc_number' => ['nullable', 'array', 'min:1'],
            'doc_number.*' => ['nullable', 'max:50'],
            'doc' => ['nullable', 'array', 'min:1'],
            'doc.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,pdf,docx', 'max:10240'],
            'doc_issued_district' => 'nullable|exists:location_hierarchy,location_code'
        ];


        return $rules;
    }
}

