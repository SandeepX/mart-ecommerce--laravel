<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/6/2020
 * Time: 2:17 PM
 */

namespace App\Modules\SystemSetting\Requests;


use App\Rules\WithoutSpaces;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MailSettingRequest extends FormRequest
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
            'mail_mailer' => ['required',Rule::in(['smtp','ses','mailgun'])],
            'mail_host' => ['required',new WithoutSpaces()],
            'mail_port' => ['required','integer'],
            'mail_username' => ['required','email'],
            'mail_password' => ['required','alpha_dash'],
            'mail_encryption' => ['required','alpha_dash'],
            'mail_from_address' => ['required','email'],
            'mail_from_name' => ['required','alpha_dash'],
        ];
    }

}