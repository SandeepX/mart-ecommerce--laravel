<?php

namespace App\Modules\ManagerDiary\Requests\Diary;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateManagerDiaryRequest extends FormRequest
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

    public function rules()
    {

        return [
            'store_name' => ['required','string','max:191'],
            'referred_store_code' => [
                'sometimes','nullable',
                Rule::unique('manager_diaries')
                    ->ignore($this->route('manager_diary'),'manager_diary_code')
                    ->whereNotNull('referred_store_code')
                    ->whereNull('deleted_at'),
                 Rule::exists('stores_detail','store_code')
                    ->whereNull('deleted_at')
            ],
            'owner_name'=>['required','string','max:191'],
            'phone_no' => ['required',
                'digits:10',
                Rule::unique('manager_diaries')
                    ->ignore($this->route('manager_diary'),'manager_diary_code')
                    ->whereNull('deleted_at'),'regex:/(9)[0-9]{9}/'],
            'alt_phone_no' => ['nullable','digits:10','regex:/(9)[0-9]{9}/'],
            'pan_no' => ['required'],
            'ward_code' => ['required',Rule::exists('location_hierarchy','location_code')
                ->where('location_type','ward')
                ->whereNull('deleted_at')
            ],
            'latitude' => ['nullable','numeric'],
            'longitude' => ['nullable','numeric'],
            'business_investment_amount'=>['required','numeric','min:1']
        ];
    }

    public function messages()
    {
        return [
            'referred_store_code.exists' => 'Selected Store is invalid',
        ];
    }

}
