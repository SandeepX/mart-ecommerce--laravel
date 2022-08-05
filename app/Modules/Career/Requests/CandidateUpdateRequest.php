<?php

namespace App\Modules\Career\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateUpdateRequest extends FormRequest
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
            'name'=>'bail|required|max:255',
            'email'=>'bail|required|email',
            'phone_number'=>'bail|required|digits:10|phone_number|regex:/(9)[0-9]{9}/',
            'gender'=>'required',
            'cover_letter'=>'bail|required',
            'cv_file'=>'bail|required|mimes:pdf|max:2048',
            'career_id'=>'required',
        ];
    }
}
