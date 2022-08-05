<?php


namespace App\Modules\Store\Requests\StorePackageTypes;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePackageUpdateRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'store_type_code' => ['required',
                Rule::exists('store_types','store_type_code')
                    ->where('is_active',1)
                    ->whereNull('deleted_at')
            ],
            'store_type_package_history_code' => ['required','max:191',
                Rule::exists('store_type_package_history','store_type_package_history_code')
                    ->where('is_active',1)
                    ->whereNull('to_date')
                    ->whereNull('deleted_at')
            ],
            'remarks' => 'required',
        ];
    }


}
