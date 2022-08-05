<?php

namespace App\Modules\ContentManagement\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SitePageRequest extends FormRequest
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
           'content' => 'required',
           'content_type' => 'required|in:about-us,privacy-policy,terms-and-conditions',
        ];
    }
}
