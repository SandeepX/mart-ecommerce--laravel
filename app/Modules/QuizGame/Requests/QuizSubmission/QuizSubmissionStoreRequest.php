<?php


namespace App\Modules\QuizGame\Requests\QuizSubmission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizSubmissionStoreRequest extends FormRequest
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

    public function prepareForValidation()
    {
        $userType = getAuthParentUserType();
        if($userType == 'manager'){
            $participator_code = getAuthManagerCode();
        }
        if ($userType == 'store'){
            $participator_code = getAuthStoreCode();
        }
        if($userType == 'normal-user'){
            $participator_code = getAuthUserCode();
        }
        $this->merge([
            'participator_code' => $participator_code,
            'participator_type' => $userType
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
            'qp_code'=> ['required','string','exists:quiz_game_passages,qp_code'],
            'participator_type' =>['bail','required',Rule::in(['manager','store','normal-user'])],
            'participator_code' =>['bail','required','string'],
            'quiz' =>['required','array','min:1'],
            'quiz.*.question' =>['required','string'],
            'quiz.*.answer' => ['required_with:quiz.*.question','nullable','string'],
            'quiz.*.question_code' => ['required_with:quiz.*.question','nullable','string','exists:quiz_game_questions,question_code']
        ];

    }


}



