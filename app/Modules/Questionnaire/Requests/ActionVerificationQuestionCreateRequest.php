<?php

namespace App\Modules\Questionnaire\Requests;

use App\Modules\GlobalNotification\Models\GlobalNotification;
use App\Modules\Questionnaire\Models\ActionVerificationQuestionnaire;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActionVerificationQuestionCreateRequest extends FormRequest
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
            'question' => 'required|string|max:1000',
            'entity' => ['required', Rule::in(ActionVerificationQuestionnaire::entity)],
            'action' => ['required', Rule::in(ActionVerificationQuestionnaire::action)],
            'is_active' => ['required',Rule::in(0,1)],
        ];
    }

}
