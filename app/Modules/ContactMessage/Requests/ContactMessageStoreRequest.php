<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/20/2020
 * Time: 5:14 PM
 */

namespace App\Modules\ContactMessage\Requests;


use Illuminate\Foundation\Http\FormRequest;

class ContactMessageStoreRequest extends FormRequest
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

        $rules = [
            'name' => 'required|max:191',
            'email' =>['required','email:rfc,dns'],
            'phone' => ['required','digits:10','regex:/(9)[0-9]{9}/'],
            'message' => ['required','max:200'],
            ];

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
        ];
    }

}