<?php

namespace App\Modules\Location\Requests\LocationHierarchy;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationHierarchyUpdateRequest extends FormRequest
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
            //'location_name' => 'required|max:100|unique:location_hierarchy,location_name,'.$this->route('location_hierarchy').',location_code',
            'location_name' => 'required|max:191',
            'location_name_devanagari' => 'required|max:191',
            //'location_code' => 'required|unique:location_hierarchy,location_code,'.$this->route('location_hierarchy').',location_code',
            'headquarter' => 'max:40',
            'latitude' => 'double',
            'longitude' => 'double',
        ];
    }
}
