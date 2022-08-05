<?php

namespace App\Modules\ContentManagement\Requests;

use App\Modules\Application\Rules\ValidateFileExtension;
use Illuminate\Foundation\Http\FormRequest;

class VisionUpdateRequest extends FormRequest
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
            'page_image'=>['sometimes',new ValidateFileExtension(["jpeg","png","jpg","svg","webp"]),'mimes:jpeg,png,jpg,svg,webp','max:50'],
            'vision_description' => 'required',
            'mission_description' => 'required',
            'is_active' => 'nullable|in:1,null',
        ];
    }
}
