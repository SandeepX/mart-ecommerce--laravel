<?php

namespace App\Modules\Lead\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadCreateRequest extends FormRequest
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
            'lead_name' => 'required|unique:leads_detail,lead_name|max:191',
            'lead_location_code' => 'required|exists:location_hierarchy,location_code',
            'lead_landmark' => 'max:191',
            'landmark_latitude' => 'numeric',
            'landmark_longitude' => 'numeric',
            'lead_phone_no' => 'required|digits:10|regex:/(9)[0-9]{9}/|unique:leads_detail,lead_phone_no',
            'lead_alternative_phone_no' => 'sometimes|nullable|numeric',
            'lead_email' =>  'email|max:191',
            'remarks' => 'max:191',
        ];
    }
}
