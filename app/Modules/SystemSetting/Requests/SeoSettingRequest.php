<?php

namespace App\Modules\SystemSetting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeoSettingRequest extends FormRequest
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
            'meta_title' => 'required|max:191',
            'meta_description' => 'required',
            'keywords' => 'required',
            'revisit_after' => 'required|integer|min:1',
            'author' => 'required|max:191',
            'sitemap_link' => 'required|max:191',
        ];
    }
}
