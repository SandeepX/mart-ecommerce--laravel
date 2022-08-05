<?php

namespace App\Modules\Home\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;

class SliderCreateRequest extends FormRequest
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

            'slider_image' => 'required|image|mimes:jpeg,png,webp|max:2048',
            'slider_url' => 'sometimes|nullable|url',

        ];

    }
}
