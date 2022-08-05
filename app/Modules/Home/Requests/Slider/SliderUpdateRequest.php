<?php

namespace App\Modules\Home\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;

class SliderUpdateRequest extends FormRequest
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

            'slider_image' => 'sometimes|required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'slider_url' => 'sometimes|nullable|url',

        ];

    }
}
