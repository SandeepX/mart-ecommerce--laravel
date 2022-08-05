<?php


namespace App\Modules\SMSProcessor\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SmsRequest extends FormRequest
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
           'to' =>'required|array|min:1',
           'to.*' =>'required|numeric|digits:10',
           'message' => 'required|string|max:100'
       ];
    }


}

