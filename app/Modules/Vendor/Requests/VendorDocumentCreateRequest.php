<?php

namespace App\Modules\Vendor\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorDocumentCreateRequest extends FormRequest
{

    public $documentSize = '8192';
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
            'document_names.*' => 'required|max:191',
            'document_files' => 'required|array',
            'document_files.*' => 'required|mimes:doc,docx,pdf|max:'.$this->documentSize.'',
        ];
    }


    public function messages()
    {
        return [
            'document_names.*.required' => 'Please Fill Document Name',
            'document_names.*.max' => 'Please Fill The Document Name less than 192 characters',
            'document_files.*.required' => 'Please Upload Document File',
            'document_files.*.mimes' => 'Please Upload The Document File of type : doc or docx or pdf',
            'document_files.*.max' => 'Please Upload The Document File Of Size : '.$this->documentSize.' KB',
        ];
    }
}
