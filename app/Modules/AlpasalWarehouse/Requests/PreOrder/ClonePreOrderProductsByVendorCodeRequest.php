<?php


namespace App\Modules\AlpasalWarehouse\Requests\PreOrder;


use App\Modules\Product\Models\ProductMaster;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClonePreOrderProductsByVendorCodeRequest extends FormRequest
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
            'vendor_code' => ['required',Rule::exists('vendors_detail','vendor_code')
                ->whereNull('deleted_at')
            ],
        ];

        return $rules;
    }

}
