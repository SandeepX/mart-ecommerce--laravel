<?php

namespace App\Modules\SystemSetting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneralSettingRequest extends FormRequest
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'admin_sidebar_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'full_address' => 'required|max:191',
            'primary_contact' => 'required|max:191',
            'secondary_contact' => 'required|max:191',
            'company_email' => 'required|email',
            'company_brief' => 'required:max:600',
            'facebook' => 'required',
            'twitter' => 'required',
            'instagram' => 'required',
            'is_maintenance_mode' => 'nullable|in:1,null',
            'ip_filtering' => 'nullable|in:1,null',
            'sms_enable' =>'nullable|in:1,null',

            'primary_bank_name'=>'nullable|string|exists:banks,bank_name',
            'primary_bank_account_number'=>'required_with:primary_bank_name',
            'primary_bank_branch'=>'required_with:primary_bank_name',
            'secondary_bank_name'=>'nullable|string|exists:banks,bank_name',
            'secondary_bank_account_number'=>'required_with:secondary_bank_name',
            'secondary_bank_branch'=>'required_with:secondary_bank_name',
        ];
    }
}
