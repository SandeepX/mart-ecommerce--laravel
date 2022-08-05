<?php

namespace App\Modules\ContentManagement\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CompanyTimelineUpdateRequest extends FormRequest
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
            'year'=>'required|unique:company_timelines,year,{$this->company_timelines->year}',
            'title' => 'required',
            'description' => 'required',
            'is_active' => 'nullable|in:1,null',
        ];
    }
}
