<?php


namespace App\Modules\QuizGame\Requests\QuizParticipator;

use App\Modules\QuizGame\Models\QuizParticipator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizParticipatorUpdateRequest extends FormRequest
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
        if ($userType == 'manager') {
            $participator_code = getAuthManagerCode();
        }
        if ($userType == 'store') {
            $participator_code = getAuthStoreCode();
        }
        if ($userType == 'normal-user') {
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
            'store_name' => ['required', 'string'],
            'store_pan_no' => [ 'required', 'numeric',
                Rule::unique('quiz_participator_detail','store_pan_no')
                    ->ignore($this->route('qpd_code'),'qpd_code')
                ],
            'store_location_ward_code' =>['required','string','exists:location_hierarchy,location_code'],
            'store_full_location' =>['required','string'],
            'recharge_phone_no' =>['bail','required','numeric',
                Rule::unique('quiz_participator_detail','recharge_phone_no')
                    ->ignore($this->route('qpd_code'),'qpd_code')
            ],
            'participator_type' =>['required',Rule::in(['manager','store','normal-user'])],
            'participator_code' =>['required','string'],
            'status_reponded_at' =>['nullable','date']
        ];

    }

}




