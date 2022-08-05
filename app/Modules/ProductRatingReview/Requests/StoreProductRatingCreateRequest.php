<?php


namespace App\Modules\ProductRatingReview\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRatingCreateRequest extends FormRequest
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
       // $authStoreCode= getAuthStoreCode();
        return [
//            'warehouse_code' => ['required', Rule::exists('store_warehouse', 'warehouse_code')
//                ->where(function ($query) use($authStoreCode){
//                $query->where('store_code', $authStoreCode);
//            })],
            'product_code' =>['required'],
            'rating' => ['required','gte:1','lte:5']
        ];
    }
}
