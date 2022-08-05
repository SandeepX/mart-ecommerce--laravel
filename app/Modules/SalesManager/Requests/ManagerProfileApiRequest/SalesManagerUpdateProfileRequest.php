<?php


namespace App\Modules\SalesManager\Requests\ManagerProfileApiRequest;

use Illuminate\Foundation\Http\FormRequest;


class SalesManagerUpdateProfileRequest extends FormRequest
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
        // dd($this->all());
        $rules = [];
        $rules['name'] = 'nullable|max:255';
        $rules['avatar'] = 'nullable|image|mimes:jpeg,png,jpg|max:10240';
        $rules['has_two_wheeler_license'] = 'nullable|boolean';
        $rules['has_four_wheeler_license'] = 'nullable|boolean';
        $rules['ward_code'] = 'nullable|exists:location_hierarchy,location_code';
        $rules['temporary_ward'] = 'nullable|exists:location_hierarchy,location_code';
        $rules['doc_issued_district'] = 'nullable|exists:location_hierarchy,location_code';

        return $rules;
    }

    public function messages()
    {
        return [
            'avatar.image' => 'profile picture should be image file',
            'avatar.mimes' => 'profile picture uploaded is in invalid format : (required :jpeg,png,jpg )'
        ];
    }


}

