<?php


namespace App\Modules\Vendor\Requests;

use App\Modules\Vendor\Models\VendorTargetMaster;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorTargetSetStoreRequest extends FormRequest
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

        $currentTime =Carbon::now('Asia/Kathmandu')->format('Y-m-d H:i:s');
        if ($this->start_date){
            $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->start_date);

        }
        else{
            $startTime=null;
        }

        if ($this->end_date){
            $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->end_date);

        }
        else{
            $endTime=null;
        }

        $rules = [

            'status' => ['nullable', Rule::in(VendorTargetMaster::STATUS)],
            //'vendor_code' =>'required|exists:vendors_detail,vendor_code',
            'name'=>'required|string|max:100',
            'province_code' =>'required',
            'district_code' =>'required',
            'municipality_code' => 'required',
            'start_date' => ['bail','required','date_format:Y-m-d H:i:s','after_or_equal:'.$currentTime],
            'end_date' => ['bail','required','date_format:Y-m-d H:i:s','after_or_equal:'.$startTime],
            'is_active' => 'nullable|boolean',
            'remark'=>'required|string|max:500'

        ];

        return $rules;
    }
}
