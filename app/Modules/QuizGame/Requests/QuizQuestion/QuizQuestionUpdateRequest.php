<?php


namespace App\Modules\QuizGame\Requests\QuizQuestion;


namespace App\Modules\QuizGame\Requests\QuizQuestion;

use Illuminate\Foundation\Http\FormRequest;

class QuizQuestionUpdateRequest extends FormRequest
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
            'question' => ['required', 'string'],
            'option_a' => ['required_with:question', 'nullable', 'string'],
            'option_b' => ['required_with:question', 'nullable', 'string'],
            'option_c' => ['required_with:question', 'nullable', 'string'],
            'option_d' => ['required_with:question', 'nullable', 'string'],
            'correct_answer' => ['required_with:question', 'nullable', 'string'],
            'points' => ['required_with:question', 'nullable', 'string'],
            'question_is_active' => ['nullable', 'boolean:0,1'],
        ];

    }

}



