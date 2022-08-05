<?php

namespace App\Modules\PromotionLinks\Requests;

use App\Modules\Application\Rules\ValidateFileExtension;
use Illuminate\Foundation\Http\FormRequest;

class PromotionLinkUpdateRequest extends FormRequest
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
            'filename' => 'sometimes|nullable|max:100|unique:promotion_links,filename,'.$this->route('promotion_link').'',
            'file' => ['nullable','max:20480',
                new ValidateFileExtension(["jpeg","png","jpg","webp","mp3","mp4"]),
                'mimes:jpeg,png,jpg,webp,mp3,mp4'
            ],
            'link_code'=>['required','string','unique:promotion_links,link_code,'.$this->route('promotion_link').''],
            'title' => ['required','string'],
            'description' => ['nullable','string'],
            'image' => [
                'nullable', 'image',
                new ValidateFileExtension(["jpeg","png","jpg","webp"]),
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ],
            'og_title' => ['nullable','string'],
            'og_description' => ['nullable'],
            'og_image' => [
                'nullable', 'image',
                new ValidateFileExtension(["jpeg","png","jpg","webp"]),
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ]
        ];
    }

}
