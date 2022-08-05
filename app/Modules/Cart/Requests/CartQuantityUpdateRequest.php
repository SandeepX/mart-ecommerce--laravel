<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 9/27/2020
 * Time: 3:09 PM
 */

namespace App\Modules\Cart\Requests;


use Illuminate\Foundation\Http\FormRequest;

class CartQuantityUpdateRequest extends FormRequest
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
            'quantity' => 'required|integer|min:1',
        ];
    }
}