<?php

namespace App\Modules\LuckyDraw\Requests\StoreLuckydraw;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLuckydrawCreateRequest extends FormRequest
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
     * Prepare the data for validation.
     * sanitize any data from the request before you apply your validation rules
     * @return void
     */
    protected function prepareForValidation()
    {
        $trimmed = preg_replace('/\s+/', ' ', $this->luckydraw_name);
        $this->merge([
            'luckydraw_name' => $trimmed,
            //'slug' => make_slug($trimmed),
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $currentTime =Carbon::now('Asia/Kathmandu')->format('Y-m-d H:i:s');

        return [
            'luckydraw_name' => 'required|max:191',
            'type' => ['required',Rule::in(['cash','goods']),],
            'prize' => 'required|max:191',
            'eligibility_sales_amount' => 'required|numeric|min:0',
            'days' => 'required|numeric|min:0|max:1000',
            'opening_time' => ['bail','required','date_format:Y-m-d H:i:s','after_or_equal:'.$currentTime],
            'pickup_time' => 'required|numeric|min:0|max:1000',
           // 'status' => ['nullable',Rule::in(['open','closed']),],
           // 'terms' => 'required|array|min:1',
            'terms.*' => 'nullable|string|min:1',
            'remarks' => 'nullable',
            'youtube_link' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'prize_name.required' => 'The StoreLuckydraw name field is required',
            'type.in' => 'Invalid Type',
            'eligibility_sales_amount.required' => 'The Eligibility Sales Amount field is required',
            'days.required' => 'The days field is required',
        ];
    }
}
