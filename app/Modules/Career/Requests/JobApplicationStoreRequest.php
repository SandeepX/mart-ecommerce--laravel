<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/18/2020
 * Time: 10:41 AM
 */

namespace App\Modules\Career\Requests;


use App\Modules\Career\Services\JobOpeningService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobApplicationStoreRequest extends FormRequest
{

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

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
        $trimmedName = preg_replace('/\s+/', ' ', $this->name);
        $this->merge([
            'name' => $trimmedName,
            //'job_opening' =>$this->route('job_opening')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $jobOpeningSlug =$this->route('job_opening');
        $jobOpening = JobOpeningService::findOrFailJobOpeningBySlugWith($jobOpeningSlug,['jobQuestions']);
        $jobQuestions = $jobOpening->jobQuestions;

        $rules = [
           // 'job_opening' => ['required',Rule::in([$jobOpening->slug])],
            'name' => 'required|max:191',
            'email' =>['required','email:rfc,dns', Rule::unique('job_applications', 'email')->where(function ($query) use($jobOpening){
                return $query->where('job_opening_code', $jobOpening->opening_code);
            })],
            'gender' => ['required',Rule::in(['m','f','other'])],
            'phone_num' => ['required','max:191'],
            'other_contacts' => ['nullable','max:191'],
            'temp_location_code' => ['required', Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                return $query->where('location_type','ward');
            })],
            'temp_local_address' => ['required','max:191'],
            'perm_location_code' => ['required',Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                return $query->where('location_type','ward');
            })],
            'perm_local_address' => ['required','max:191'],
            'cv' => ['required','file','mimes:doc,docx,pdf,rtf,odt','max:2048']
        ];

        if (count($jobQuestions) > 0){

            $jobQuestionsCode =$jobQuestions->pluck('question_code')->toArray();
            $rules['job_questions'] = ['required', 'array'];
            $rules[ 'job_questions.*'] =['required','distinct',Rule::in($jobQuestionsCode)];
            $rules['job_answers'] =['required','array'];
            $rules[ 'job_answers.*'] =['required','max:50000'];

        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //'job_opening.required' => 'Invalid job opening',
            //'job_opening.in' => 'Invalid job opening',
            'email.unique' => 'This email has already applied for the job',
            'temp_location_code.required' => 'Temporary location required',
            'temp_location_code.exists' => 'Invalid temporary location',
            'perm_location_code.required' => 'Permanent location required',
            'perm_location_code.exists' => 'Invalid permanent location',
            'temp_local_address.required' => 'Temporary local address required',
            'temp_local_address.max' => 'Too long temporary local address',
            'perm_local_address.required' => 'Permanent local address required',
            'perm_local_address.max' => 'Too long permanent local address',
            'job_questions.required' => 'Job questions required',
            'job_questions.*.required' => 'Job question required',
            'job_answers.required' => 'Answers required',
            'job_answers.*.required' => 'Answer required',

        ];
    }

}