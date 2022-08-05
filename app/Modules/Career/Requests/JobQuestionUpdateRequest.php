<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/15/2020
 * Time: 3:03 PM
 */

namespace App\Modules\Career\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobQuestionUpdateRequest extends FormRequest
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
     * Prepare the data for validation.
     * sanitize any data from the request before you apply your validation rules
     * @return void
     */
    protected function prepareForValidation()
    {
       // $trimmed = trim($this->question);
        $trimmedQues = preg_replace('/\s+/', ' ', $this->question);
        $this->merge([
            'question' => $trimmedQues,
            'slug' => make_slug($trimmedQues),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       // dd($this->route('job_question'));
        $questionCode =$this->route('job_question');
        return [
            'question' => ['required','max:50',Rule::unique('job_questions','question')->ignore($questionCode,'question_code')],
            'slug' => ['required','max:50',Rule::unique('job_questions','slug')->ignore($questionCode,'question_code')],

            'status' => ['nullable',Rule::in(['on','off'])],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'question.unique' => 'Given question already exists',
            'slug.unique' => 'Slug already exists, try maintaining spaces',
        ];
    }
}