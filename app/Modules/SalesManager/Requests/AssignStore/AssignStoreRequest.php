<?php


namespace App\Modules\SalesManager\Requests\AssignStore;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignStoreRequest extends FormRequest
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
            'manager_code' => 'required|string|exists:managers_detail,manager_code',
            'store_code' => 'required|array|min:1',
            'store_code.*' => 'required|string|exists:stores_detail,store_code'
        ];
    }

}

