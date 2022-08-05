<?php

namespace App\Modules\Location\Requests\LocationHierarchy;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocationHierarchyCreateRequest extends FormRequest
{

    private $upperLocationCode;
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'upper_location_code' => $this->upperLocationCode,
        ];
    }

    protected function prepareForValidation()
    {
        $inputLocationType = $this->get('location_type');
        $upperLocationCode=' ';
        if ($inputLocationType == 'district'){
            $this->upperLocationCode='province';
         $upperLocationCode = $this->get('province');
        }

        elseIf($inputLocationType == 'municipality'){
            $this->upperLocationCode='district';
            $upperLocationCode = $this->get('district');
        }

        elseIf($inputLocationType == 'ward'){
            $this->upperLocationCode='municipality';
            $upperLocationCode = $this->get('municipality');
        }

        $this->merge([
            'upper_location_code' =>$upperLocationCode
        ]);
    }

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
        //$upperLocationCodes = LocationHierarchy::where('location_type','ward')->pluck('location_code')->toArray();
        $inputLocationType = $this->get('location_type');

        $rules = [
            //  'location_name' => 'required|max:100|unique:location_hierarchy,location_name',
            'location_type' => ['required', Rule::in(['district', 'municipality', 'ward'])],
            /*'location_name' => ['required', 'max:191', Rule::unique('location_hierarchy', 'location_name')->where(function ($query) {
                $query->where('location_type', 'ward');
            })],
            'location_name_devanagari' => 'required|max:191',*/
            // 'upper_location_code' => ['required',Rule::in($upperLocationCodes)],
            //'location_code' => 'required|unique:location_hierarchy,location_code',
            // 'headquarter' => 'max:40',
            // 'latitude' => 'double',
            // 'longitude' => 'double',
        ];


        if ($inputLocationType == 'district') {
            $provinceLocationCode = $this->get('province');
            $rules['upper_location_code'] = ['required', Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                $query->where('location_type', 'province');
            })];
            $rules['location_name'] = ['required', 'max:191', Rule::unique('location_hierarchy', 'location_name')
                ->where(function ($query) use ($provinceLocationCode) {
                    $query->where('upper_location_code', $provinceLocationCode);
                })
            ];
            $rules['location_name_devanagari'] = ['required', 'max:191', Rule::unique('location_hierarchy', 'location_name_devanagari')
                ->where(function ($query) use ($provinceLocationCode) {
                    $query->where('upper_location_code', $provinceLocationCode);
                })
            ];
        }

        elseif ($inputLocationType == 'municipality') {
            $districtLocationCode = $this->get('district');
            $rules['upper_location_code'] = ['required', Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                $query->where('location_type', 'district');
            })];
            $rules['location_name'] = ['required', 'max:191', Rule::unique('location_hierarchy', 'location_name')
                ->where(function ($query) use ($districtLocationCode) {
                    $query->where('upper_location_code', $districtLocationCode);
                })
            ];
            $rules['location_name_devanagari'] = ['required', 'max:191', Rule::unique('location_hierarchy', 'location_name_devanagari')
                ->where(function ($query) use ($districtLocationCode) {
                    $query->where('upper_location_code', $districtLocationCode);
                })
            ];
        }

        elseif ($inputLocationType == 'ward') {
            $municipalityLocationCode = $this->get('municipality');
            $rules['upper_location_code'] = ['required', Rule::exists('location_hierarchy', 'location_code')->where(function ($query) {
                $query->where('location_type', 'municipality');
            })];
            $rules['location_name'] = ['required', 'max:191', Rule::unique('location_hierarchy', 'location_name')
                ->where(function ($query) use ($municipalityLocationCode) {
                    $query->where('upper_location_code', $municipalityLocationCode);
                })
            ];
            $rules['location_name_devanagari'] = ['required', 'max:191', Rule::unique('location_hierarchy', 'location_name_devanagari')
                ->where(function ($query) use ($municipalityLocationCode) {
                    $query->where('upper_location_code', $municipalityLocationCode);
                })
            ];
        }
        return $rules;
    }
}
