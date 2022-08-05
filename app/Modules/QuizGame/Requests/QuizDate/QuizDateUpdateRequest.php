<?php


namespace App\Modules\QuizGame\Requests\QuizDate;

use Illuminate\Foundation\Http\FormRequest;


class QuizDateUpdateRequest extends FormRequest
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


    protected function prepareForValidation()
    {
        $this->merge([
            'quiz_passage_date' => explode(",", $this->quiz_passage_date),
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
            'quiz_passage_date' => ['bail', 'required', 'array', 'min:1'],
            'quiz_passage_date.*' => ['bail', 'required', 'date'],
        ];

    }


}




