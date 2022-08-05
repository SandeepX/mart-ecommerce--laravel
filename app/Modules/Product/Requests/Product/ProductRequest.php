<?php

namespace App\Modules\Product\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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

            'product_name' => 'required|max:191',
            'description' => 'required',
            'brand_code' => 'required|exists:brands,brand_code', // category anushar ko brands bhitra hunu parney
            'category_code' => 'required|exists:category_master,category_code',
            'sensitivity_code' => 'required|exists:product_sensitivities,sensitivity_code',
            'remarks' => 'nullable|max:60',
            //'variant_tag' => 'required|boolean',
            'video_link' => 'sometimes|nullable|url|starts_with:https://www.youtube.com/watch?v=',
            'highlights' => 'required|array|max:5',
            'proceed_with_variants' => ['nullable', Rule::in(["1","0"])],
            'is_taxable' => ['required', Rule::in(["1","0"])],
        ];
    }
}
