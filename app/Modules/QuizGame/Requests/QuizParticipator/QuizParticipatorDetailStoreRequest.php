<?php


namespace App\Modules\QuizGame\Requests\QuizParticipator;

use App\Modules\QuizGame\Models\QuizParticipator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizParticipatorDetailStoreRequest extends FormRequest
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
            'store_name' => ['bail', 'required', 'string'],
            'store_pan_no' => ['bail', 'required', 'numeric','unique:quiz_participator_detail'],
            'store_location_ward_code' =>['bail','required','string','exists:location_hierarchy,location_code'],
            'store_full_location' =>['bail','required','string'],
            'recharge_phone_no' =>['bail','required','numeric','unique:quiz_participator_detail'],
            'participator_type' =>['bail','required',Rule::in(['manager','store','normal-user'])],
            'participator_code' =>['bail','required','string'],
            'status_reponded_at' =>['bail','nullable','date']
        ];

    }


}



