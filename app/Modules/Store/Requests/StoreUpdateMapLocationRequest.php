<?php

namespace App\Modules\Store\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateMapLocationRequest extends FormRequest
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

            'latitude' => 'required|numeric|max:191',
            'longitude' => 'required|numeric|max:191',

        ];
    }


    public function messages()
    {
        return [
            'latitude.required' => 'The store latitude field is required',
            'longitude.required' => 'The store longitude field is required',

        ];
    }
}
