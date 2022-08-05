<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/27/2020
 * Time: 4:07 PM
 */

namespace App\Modules\Cart\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CartMassDeleteRequest extends FormRequest
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
            'cart_codes' => 'required|array',
            'cart_codes.*' => ['sometimes','nullable', Rule::exists('carts', 'cart_code')->where(function ($query) {
                return $query->where('user_code',getAuthUserCode());
            })],
        ];
    }

}