<?php

namespace App\Modules\User\Requests;

use App\Modules\SalesManager\Models\Manager;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\SalesManager\Models\SalesManagerRegistrationStatus;

class UpdateStatusAndAssignAreaRequest extends FormRequest
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
            'status' => ['required', Rule::in(Manager::STATUS)],
            'remarks' =>'nullable|required_if:status,==,rejected |string',
            'assigned_area_code' =>'nullable|required_if:status,==,approved|exists:location_hierarchy,location_code'
        ];
    }
}

