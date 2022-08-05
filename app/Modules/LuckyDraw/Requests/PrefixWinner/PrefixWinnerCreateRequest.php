<?php

namespace App\Modules\LuckyDraw\Requests\PrefixWinner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PrefixWinnerCreateRequest extends FormRequest
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
        // dd($this->all());
        return [
            'store_luckydraw_code' => ['required','max:191',
                Rule::exists('store_luckydraws','store_luckydraw_code')],
            'store_code' => ['required','array','max:191',Rule::exists('stores_detail','store_code')],
            'store_code.*' => ['required','max:191',Rule::exists('stores_detail','store_code')],
            'remarks' => 'nullable',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'store_luckydraw_code.exists' => 'Invalid Store Lucky Draw',
            'store_code.exists' => 'Invalid Store',
        ];
    }
}
