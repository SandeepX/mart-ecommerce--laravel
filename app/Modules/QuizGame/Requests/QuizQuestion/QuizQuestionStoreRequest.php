<?php


namespace App\Modules\QuizGame\Requests\QuizQuestion;

use Illuminate\Foundation\Http\FormRequest;

class QuizQuestionStoreRequest extends FormRequest
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
            'quiz' =>['required','array','min:1'],
            'quiz.*.question' =>['required','string'],
            'quiz.*.option_a' =>['required_with:quiz.*.question','nullable','string'],
            'quiz.*.option_b' =>['required_with:quiz.*.question','nullable','string'],
            'quiz.*.option_c' =>['required_with:quiz.*.question','nullable','string'],
            'quiz.*.option_d' =>['required_with:quiz.*.question','nullable','string'],
            'quiz.*.correct_answer' =>['required_with:quiz.*.question','nullable','string'],
            'quiz.*.points' =>['required_with:quiz.*.question','nullable','string'],
            'quiz.*.question_is_active' =>['nullable','boolean:0,1'],
        ];

    }


}


