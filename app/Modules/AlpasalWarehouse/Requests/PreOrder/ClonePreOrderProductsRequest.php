<?php


namespace App\Modules\AlpasalWarehouse\Requests\PreOrder;


use App\Modules\Product\Models\ProductMaster;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClonePreOrderProductsRequest extends FormRequest
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
            'source_listing_code' => ['required',Rule::exists('warehouse_preorder_listings','warehouse_preorder_listing_code')
                ->whereNull('deleted_at')
            ],
            'destination_listing_code' => ['required',Rule::exists('warehouse_preorder_listings','warehouse_preorder_listing_code')
                ->whereNull('deleted_at')
                    ],
        ];

        return $rules;
    }

}
