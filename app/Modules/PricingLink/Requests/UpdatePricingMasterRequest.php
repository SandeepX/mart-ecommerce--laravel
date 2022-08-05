<?php


namespace App\Modules\PricingLink\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdatePricingMasterRequest extends FormRequest
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
            'warehouse_code' => ['required', 'string','exists:warehouses,warehouse_code'],
            'link' => ['nullable', 'string'],
            'link_code' => ['nullable', 'string'],
            'password' => ['required', 'string'],
            'expires_at' => 'required|date|after:' . Carbon::now()->format('Y-m-d H:i:s'),
            'is_active' => ['nullable', 'boolean', Rule::in([1, 0])]
        ];

        return $rule;
    }


}

