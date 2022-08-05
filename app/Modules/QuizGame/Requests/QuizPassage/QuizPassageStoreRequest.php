<?php


namespace App\Modules\QuizGame\Requests\QuizPassage;


use Illuminate\Foundation\Http\FormRequest;


class QuizPassageStoreRequest extends FormRequest
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
            'passage_title' =>['bail','required','string'],
            'passage' =>['bail','required'],
            'passage_is_active' =>['bail','required','boolean'],
            'total_passage_points' =>['bail','nullable','numeric']
        ];

    }


}


