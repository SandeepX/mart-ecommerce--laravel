<?php

namespace App\Modules\ContentManagement\Requests;

use App\Modules\ContentManagement\Models\Faq;
use Illuminate\Foundation\Http\FormRequest;

class FaqUpdateRequest extends FormRequest
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
        $faq = Faq::find($this->route('faq'));
        return [
           'question' => 'required|max:191',
           'answer' => 'required',
           'priority' => 'required|integer|min:1|unique:faqs,priority,'.$faq->priority.',priority',
           'is_active' => 'nullable|in:1,null',
        ];
    }
}
