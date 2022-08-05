<?php


namespace App\Modules\B2cCustomer\Requests;


use App\Modules\User\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserProfileUpdateRequest extends FormRequest
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
        $rules = [];
        $rules['name'] = 'nullable|max:255';
        $rules['gender'] = ['nullable', Rule::in(User::GENDER)];
        $rules['avatar'] = 'nullable|image|mimes:jpeg,png,jpg|max:5048';
//        $rules['has_two_wheeler_license'] = 'nullable|boolean';
//        $rules['has_four_wheeler_license'] = 'nullable|boolean';
        $rules['ward_code'] = 'nullable|exists:location_hierarchy,location_code';
        $rules['temporary_ward'] = 'nullable|exists:location_hierarchy,location_code';
        $rules['doc_issued_district'] = 'nullable|exists:location_hierarchy,location_code';

        return $rules;
    }

    public function messages()
    {
        return [
            'avatar.image' => 'profile picture should be image file',
            'has_four_wheeler_license.required' => 'Vehicle Information is Required',
            'avatar.mimes' => 'profile picture uploaded is in invalid format : (required :jpeg,png,jpg )'
        ];
    }


}


