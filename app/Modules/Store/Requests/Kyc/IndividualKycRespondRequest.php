<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/20/2020
 * Time: 6:00 PM
 */

namespace App\Modules\Store\Requests\Kyc;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndividualKycRespondRequest extends FormRequest
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

        $rules = [
            'verification_status' => ['required',Rule::in(['verified','rejected'])],
            'remarks'=>['required_if:verification_status,rejected','max:2000'],
        ];

        return $rules;
    }
}