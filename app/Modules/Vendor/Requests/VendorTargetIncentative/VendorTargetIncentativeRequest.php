<?php


namespace App\Modules\Vendor\Requests\VendorTargetIncentative;

use App\Modules\Vendor\Models\VendorTargetIncentive;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorTargetIncentativeRequest extends FormRequest
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

            'vendor_target_master_code' =>'required|exists:vendor_target_master,vendor_target_master_code',
            'product_code'  =>'required|exists:products_master,product_code',
            'product_variant_code'  =>'nullable|exists:product_variants,product_variant_code',
            'starting_range' =>'required|numeric',
            'end_range' => 'bail|required|numeric|gt:starting_range',
            'incentive_type' => ['nullable', Rule::in(VendorTargetIncentive::INCENTIVE_TYPE)],
            'has_meet_target' => 'nullable|boolean',
            'incentive_value' => 'required|numeric'

        ];

        return $rules;
    }
}
