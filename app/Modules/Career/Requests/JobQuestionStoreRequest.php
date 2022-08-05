<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/15/2020
 * Time: 12:55 PM
 */

namespace App\Modules\Career\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobQuestionStoreRequest extends FormRequest
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
        return [
            'question' => 'required|max:50|unique:job_questions,question',
            'slug' => 'required|max:50|unique:job_questions,slug',
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