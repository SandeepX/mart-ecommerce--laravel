<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/16/2020
 * Time: 5:02 PM
 */

namespace App\Modules\Career\Requests;


use App\Modules\Career\Services\JobOpeningService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobOpeningStoreRequest extends FormRequest
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
        $trimmedTitle = preg_replace('/\s+/', ' ', $this->title);
        $this->merge([
            'title' => $trimmedTitle,
            'slug' => make_slug($trimmedTitle),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $validJobTypes = JobOpeningService::getAllJobTypesValue();

        return [
            'title' => 'required|max:191|unique:job_openings,title',
            'slug' => 'required|max:191|unique:job_openings,slug',
            'location' => 'required|max:191',
            'description' => 'required|max:60000',
            'requirements' => 'required|max:60000',
            'salary' => 'required|max:100',
            'job_type' => ['required', Rule::in($validJobTypes)],
            'is_active' => ['nullable', Rule::in(['on', 'off'])],

            'job_question_code' => ['sometimes','nullable', 'array'],
            'job_question_code.*' => ['sometimes','nullable', 'distinct',
                Rule::exists('job_questions', 'question_code')->where(function ($query) {
                    $query->where('is_active', 1);
                })],
            'question_priority' => ['sometimes','nullable','array'],
            'question_priority.*' => ['bail','required_with:job_question_code.*','nullable','integer','distinct'],

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
            'is_active.in' => 'Invalid active status',
            'job_question_code.*.exists' => 'Invalid job question',
            'question_priority.*.integer' => 'Question position must be an integer',
            'question_priority.*.distinct' => 'Question position must be distinct',
            'question_priority.*.required_with' => 'Position is required if question is included',
        ];
    }

}