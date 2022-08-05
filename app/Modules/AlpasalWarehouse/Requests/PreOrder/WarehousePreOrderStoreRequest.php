<?php


namespace App\Modules\AlpasalWarehouse\Requests\PreOrder;


use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WarehousePreOrderStoreRequest extends FormRequest
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

    /*protected function prepareForValidation()
    {

        $this->merge([
            'start_time' => date('Y-d-m H:i:s',strtotime($this->start_time)),

        ]);
    }*/

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $currentTime =Carbon::now('Asia/Kathmandu')->format('Y-m-d H:i:s');

       // $this->start_time = date('Y-d-m H:i:s',strtotime($this->start_time));
        if ($this->start_time){
            $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->start_time);
          //  $startTime = $startTime->addDays(1);
        }
       else{
           $startTime=null;
       }

       if ($this->end_time){
           $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->end_time);
          // $endTime = $endTime->addDays(1);
       }
       else{
           $endTime=null;
       }

        return [
            'pre_order_name'=> ['required','max:255'],
            'start_time' => ['bail','required','date_format:Y-m-d H:i:s','after_or_equal:'.$currentTime],
            'end_time' => ['bail','required','date_format:Y-m-d H:i:s','after_or_equal:'.$startTime],
            'finalization_time' => ['bail','required','date_format:Y-m-d H:i:s','after_or_equal:'.$endTime],
            'is_active' => ['nullable',Rule::in(['on','off'])],
            'banner_image'=>['required','image','mimes:jpeg,png,jpg','max:2048'],
        ];
    }

    public function messages()
    {
//        return [
//            'start_time.after_or_equal' => 'The start time must be a date after or equal to current time.',
//            'end_time.after_or_equal' => 'The end time must be at least one day after start time.',
//            'finalization_time.after_or_equal' => 'The finalization time must be at least one day after end time.'
//
//        ];
        return [
            'start_time.after_or_equal' => 'The start time must be a date after or equal to current time.',
            'end_time.after_or_equal' => 'The end time must be after start time.',
            'finalization_time.after_or_equal' => 'The finalization time must be after end time.'

        ];
    }

}
