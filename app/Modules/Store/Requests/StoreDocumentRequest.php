<?php

namespace App\Modules\Store\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
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
            'document_names' => 'required|array',
            'document_name.*' => 'required|max:191',
            'document_files' => 'required|array',
            'document_file.*' => 'required|file|mimes:doc,docx,pdf|max:2048',
        ];
    }
}
