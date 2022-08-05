<?php

namespace App\Modules\Product\Requests\Product;

use App\Modules\Application\Rules\ValidateFileExtension;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductVariantRequest extends FormRequest
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
//        return [
//            'combinations' => 'nullable|array',
//            'combinations.*.combination_values' => 'nullable|array',
//            'combination_values.*.variant_value_name' => 'nullable|exists:variant_values,variant_value_name',
//            'combination_values.*.variant_value_code' => 'nullable|exists:variant_values,variant_value_code',
//            'combination_values.*.parent_variant_name' => 'nullable|exists:variants,variant_name',
//            'combination_values.*.parent_variant_code' => 'nullable|exists:variants,variant_code',
//            'combinations.*.images' => 'nullable|array|max:3',
//            'combinaions.*.images.*' => [
//                'nullable',
//                'image',
//                 new ValidateFileExtension(["jpeg","png","jpg","webp"]),
//                'mimes:jpeg,png,jpg,webp',
//                'max:2048'
//            ]
//                ];



        $rules= [
            'selected_attribute' =>['nullable','array'],
            'variant_groups' => ['nullable','array']
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
            $rules['variant_groups.*.combinations'] =['required','array','min:1'];
            $rules['variant_groups.*.combinations.*.combination_values'] =['required','array','min:1'];
            $rules['variant_groups.*.combinations.*.combination_values.*.variant_value_name'] =['required'];
            $rules['variant_groups.*.combinations.*.combination_values.*.variant_value_code'] =['required'];
            $rules['variant_groups.*.combinations.*.images'] =['nullable','array'];
            $rules['variant_groups.*.combinations.*.images.*'] =[
                'nullable',
                'image',
                new ValidateFileExtension(["jpeg","png","jpg","webp"]),
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ];
            $rules['variant_groups.*.bulk_images'] =['nullable','array'];
            $rules['variant_groups.*.bulk_images.*'] =[
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
