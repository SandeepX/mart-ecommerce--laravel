<?php

namespace App\Modules\Store\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
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
   /* public function rules()
    {
        return [
           'product_code' => 'required|array',
           'product_code.*' => ['required',Rule::exists('carts', 'product_code')->where(function ($query) {
               $query->where('user_code', getAuthUserCode());
           })],
           //'product_code.*' => 'required|exists:products_master,product_code',
           'product_variant_code' => 'required|array',
           'product_variant_code.*' =>['nullable',Rule::exists('carts', 'product_variant_code')->where(function ($query) {
               $query->where('user_code', getAuthUserCode());
           })],
           //'product_variant_code.*' => 'nullable|exists:product_variants,product_variant_code',
           'quantity' => 'required|array',
           'quantity.*' => 'required|integer|min:1',
        ];
    }*/

    public function rules()
    {
        return [
            'cart_codes' => 'required|array',
            'cart_codes.*' => ['required',Rule::exists('carts', 'cart_code')->where(function ($query) {
                $query->where('user_code', getAuthUserCode());
            })],
        ];
    }

    public function messages()
    {
        return [
            'cart_codes.required' => 'Cart codes required',
            'cart_codes.*.exists' => 'Invalid cart',
        ];
    }
}
