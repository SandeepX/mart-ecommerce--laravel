<?php


namespace App\Modules\PricingLink\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreInfoForOtpRequest extends FormRequest
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
        $rule = [
            'pricing_master_code' => ['required', 'string','exists:pricing_master,pricing_master_code'],
            'mobile_number' => 'required|max:191',
            'full_name' => 'required|max:191',
            'location_code' => [
                'required',
                Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                    $query->where('location_type', 'ward');
                })
            ],
        ];

        return $rule;
    }


}

