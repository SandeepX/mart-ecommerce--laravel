<?php

namespace App\Modules\SystemSetting\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MobileAppDeploymentLogRequest extends FormRequest
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
            'manager_version' => 'nullable|string',
            'manager_build_number' => 'nullable|string',
            'store_version' => 'nullable|string',
            'store_build_number' => 'nullable|string',
        ];
    }

}
