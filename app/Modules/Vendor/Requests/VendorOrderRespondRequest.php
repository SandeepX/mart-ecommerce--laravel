<?php

namespace App\Modules\Vendor\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorOrderRespondRequest extends FormRequest
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
            'order_received_status' => 'required|in:accepted,in_process,ready_for_dispatch,cancelled',
            'cancellation_code' => $this->order_received_status == 'cancelled' ? 'required|exists:cancellation_para' : '',
            'cancellation_reason' => $this->order_received_status == 'cancelled' ? 'required|max:191' : '',
        ];
    }
}
