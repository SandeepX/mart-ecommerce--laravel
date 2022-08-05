<?php

namespace App\Modules\Store\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderStatusRequest extends FormRequest
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
           'delivery_status' => 'required|in:dispatched,processing,cancelled',
           'order_items' => ['nullable','array','min:1'],
           'acceptance_status'=>['nullable','array','min:1'],
            'acceptance_status.*' => ['nullable',Rule::in('accepted','rejected')],
            'dispatchable_quantity'=>['nullable','array','min:1'],
            'dispatchable_quantity.*' => ['nullable','min:1'],
           'remarks' => ['required','string','max:1000'],
        ];
    }
}
