<?php

namespace App\Modules\PromotionLinks\Requests;

use App\Modules\Application\Rules\ValidateFileExtension;
use Illuminate\Foundation\Http\FormRequest;
use Sabberworm\CSS\Rule\Rule;

class PromotionLinkCreateRequest extends FormRequest
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
            'filename' => ['sometimes','nullable','required_with:file','max:100','unique:promotion_links,filename'],
            'file' => ['required_with:filename','max:20480',
                new ValidateFileExtension(["jpeg","png","jpg","webp","mp3","mp4"]),
                'mimes:jpeg,png,jpg,webp,mp3,mp4'
            ],
            'link_code' => ['required','string','unique:promotion_links,link_code'],
            'title' => ['required','string'],
            'description' => ['nullable','string'],
            'image' => [
                'nullable', 'image',
                new ValidateFileExtension(["jpeg","png","jpg","webp"]),
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ],
            'og_title' => ['nullable','string'],
            'og_description' => ['nullable','string'],
            'og_image' => [
                'nullable', 'image',
                new ValidateFileExtension(["jpeg","png","jpg","webp"]),
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ]
        ];
    }

}
