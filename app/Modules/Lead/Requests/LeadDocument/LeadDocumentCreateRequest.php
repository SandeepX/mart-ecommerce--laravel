<?php

namespace App\Modules\Lead\Requests\LeadDocument;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadDocumentCreateRequest extends FormRequest
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
            'document_types' => 'required|array',
            'document_type.*' => ['required',Rule::in(array_keys(config('lead-document-types')))],
            'document_files' => 'required|array',
            'document_file.*' => 'required|file|mimes:jpg,png,jpeg,doc,docx,pdf|max:2048',
        ];
    }
}
