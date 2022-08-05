<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/4/2020
 * Time: 2:11 PM
 */

namespace App\Modules\SystemSetting\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IpAccessStoreRequest extends FormRequest
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
        return [
            'ip_name' => 'required|max:190|unique:ip_accesses,ip_name',
            'ip_address' => 'required|ip|unique:ip_accesses,ip_address',
            'is_allowed' => ['nullable',Rule::in(['on','off'])],
        ];
    }
}