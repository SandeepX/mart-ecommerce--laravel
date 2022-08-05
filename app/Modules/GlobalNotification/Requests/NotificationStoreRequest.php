<?php

namespace App\Modules\GlobalNotification\Requests;

use App\Modules\GlobalNotification\Models\GlobalNotification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationStoreRequest extends FormRequest
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
            'message' => 'required|string|max:1000',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,docx,doc,xls,ppt,pdf|max:2048',
            'link' => 'nullable|url',
            'created_for' => ['required', Rule::in(GlobalNotification::created_for)],
            'start_date' => 'required|date|after:yesterday',
            'end_date' => 'nullable|date|after:start_date',
            'created_by' => 'nullable|string|exists:users,user_code',
            'is_active' => 'nullable',

        ];
    }

}
