<?php


namespace App\Modules\PricingLink\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CreatePricingMasterRequest extends FormRequest
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
        $date=Carbon::now('Asia/Kathmandu')->format('Y-m-d h:i:s');

        $rule = [
            'warehouse_code' => ['required', 'string','exists:warehouses,warehouse_code'],
            'link' => ['required', 'string'],
            'link_code' => ['required', 'string'],
            'password' => ['required', 'string'],
            'expires_at' => 'required|after:'.$date,
            'is_active' => ['nullable', 'boolean', Rule::in([1, 0])]
        ];

        return $rule;
    }


}

