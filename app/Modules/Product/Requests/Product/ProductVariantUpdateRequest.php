<?php

namespace App\Modules\Product\Requests\Product;

use App\Modules\Application\Rules\ValidateFileExtension;
use App\Modules\Vendor\Rules\CheckGroupCodeIsRequired;
use App\Modules\Vendor\Rules\CheckProductVariantCodeIsRequired;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductVariantUpdateRequest extends FormRequest
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

//        if(request()->proceed_with_variants){
//              $rules =  [
//                'edit_combinations' => 'required|array',
//                'edit_combinations.*.combination_values' => 'required|array',
//                'combination_values.*.variant_value_name' => 'required|exists:variant_values,variant_value_name',
//                'combination_values.*.variant_value_code' => 'required|exists:variant_values,variant_value_code',
//                'combination_values.*.parent_variant_name' => 'required|exists:variants,variant_name',
//                'combination_values.*.parent_variant_code' => 'required|exists:variants,variant_code',
//                'edit_combinations.*.images' => 'required|array|max:3',
//                'edit_combinations.*.images.*' => [
//                    'required',
//                    'image',
//                    'mimes:jpeg,png,jpg',
//                    'max:2048'
//                ],
//
//            ];
//        }else{
//            $rules =  [
//                'edit_combinations' => 'nullable|array',
//                'edit_combinations.*.combination_values' => 'nullable|array',
//                'combination_values.*.variant_value_name' => 'nullable|exists:variant_values,variant_value_name',
//                'combination_values.*.variant_value_code' => 'nullable|exists:variant_values,variant_value_code',
//                'combination_values.*.parent_variant_name' => 'nullable|exists:variants,variant_name',
//                'combination_values.*.parent_variant_code' => 'nullable|exists:variants,variant_code',
//                'edit_combinations.*.images' => 'nullable|array|max:3',
//                'edit_combinations.*.images.*' => [
//                    'nullable',
//                    'image',
//                    new ValidateFileExtension(["jpeg","png","jpg","webp"]),
//                    'mimes:jpeg,png,jpg,webp',
//                    'max:2048'
//                ],
//            ];
//

        $rules= [
            'selected_attribute' =>['nullable','array'],
            'variant_groups' => ['nullable','array'],
        ];
        if($this->selected_attribute){
            $rules['selected_attribute.*.variant_code'] = ['required','distinct',
                Rule::exists('variants','variant_code')
                    ->whereNull('deleted_at')
            ];
            $rules['selected_attribute.*.variant_name'] =['required','string'];
        }
        if ($this->variant_groups){
            $rules['variant_groups.*.group_name'] =['required','string'];
            $rules['variant_groups.*.group_vv_code'] =['required','string'];
            $rules['variant_groups.*.group_status'] =['nullable','string'];
            $rules['variant_groups.*.product_variant_group_code'] =[ new CheckGroupCodeIsRequired() ];
            $rules['variant_groups.*.combinations'] =['required','array','min:1'];
            $rules['variant_groups.*.combinations.*.combination_status'] =['nullable'];
            $rules['variant_groups.*.combinations.*.product_variant_code'] =[new CheckProductVariantCodeIsRequired()];
            $rules['variant_groups.*.combinations.*.combination_values'] =['required','array','min:1'];
            $rules['variant_groups.*.combinations.*.combination_values.*.variant_value_name'] =['required'];
            $rules['variant_groups.*.combinations.*.combination_values.*.variant_value_code'] =['required'];
            $rules['variant_groups.*.combinations.*.images'] =['nullable','array'];
            $rules['variant_groups.*.combinations.*.images.*'] =['nullable',
                'image',
                new ValidateFileExtension(["jpeg","png","jpg","webp"]),
                'mimes:jpeg,png,jpg,webp',
                'max:2048'];
            $rules['variant_groups.*.bulk_images'] =['nullable','array'];
            $rules['variant_groups.*.bulk_images.*'] = [
                    'nullable',
                    'image',
                    new ValidateFileExtension(["jpeg","png","jpg","webp"]),
                    'mimes:jpeg,png,jpg,webp',
                    'max:2048'
                ];
        }


        return $rules;
    }
}
