<?php

namespace App\Modules\ContentManagement\Requests;

use App\Modules\ContentManagement\Models\StaticPageImage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaticPageImageRequest extends FormRequest
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
        if ($this->isMethod('put')) {
            $rules = [
                        'image' => 'sometimes|file|mimes:jpeg,png,jpg,svg|max:2048',
                        'page_name' => ['required', Rule::in(StaticPageImage::PAGE_NAMES)],
                        'created_by' => 'nullable|string|exists:users,user_code',
                        'updated_by' => 'nullable|string|exists:users,user_code'

                      ];
        }else{
            $rules = ['image' => 'required|file|mimes:jpeg,png,jpg,svg|max:2048',
                    'page_name' => ['required', Rule::in(StaticPageImage::PAGE_NAMES)],
                    'created_by' => 'nullable|string|exists:users,user_code',
                    'updated_by' => 'nullable|string|exists:users,user_code'
            ];

        }
        return $rules;

    }

}

