<?php


namespace App\Modules\QuizGame\Requests\QuizParticipator;

use App\Modules\QuizGame\Models\QuizParticipator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizParticipatorChangeStatusRequest extends FormRequest
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
            'status' =>['required',Rule::in(QuizParticipator::STATUS)],
            'remarks' =>['nullable','string','required_if:status,rejected'],

        ];

    }


}





