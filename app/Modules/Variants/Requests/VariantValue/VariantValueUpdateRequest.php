<?php

namespace App\Modules\Variants\Requests\VariantValue;

use Illuminate\Foundation\Http\FormRequest;

class VariantValueUpdateRequest extends FormRequest
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
        $trimmedName = preg_replace('/\s+/', ' ', $this->variant_value_name);
        $this->merge([
            'variant_value_name' => $trimmedName
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'variant_value_name' => 'required|max:20',
        ];
    }
}
