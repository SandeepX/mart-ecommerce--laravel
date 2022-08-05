<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/4/2020
 * Time: 3:01 PM
 */

namespace App\Modules\SystemSetting\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IpAccessUpdateRequest extends FormRequest
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
     * Prepare the data for validation.
     * sanitize any data from the request before you apply your validation rules
     * @return void
     */
    protected function prepareForValidation()
    {
        $trimmedName= preg_replace('/\s+/', ' ', $this->ip_name);
        $this->merge([
            'ip_name' => $trimmedName,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $ipAccessCode =$this->route('ip_access_setting');
        return [
            'ip_name' => ['required','max:50',Rule::unique('ip_accesses','ip_name')->ignore($ipAccessCode,'ip_access_code')],
            'ip_address' => ['required','max:50',Rule::unique('ip_accesses','ip_address')->ignore($ipAccessCode,'ip_access_code')],
            'is_allowed' => ['nullable','sometimes',Rule::in(['on','off'])],
        ];
    }
}