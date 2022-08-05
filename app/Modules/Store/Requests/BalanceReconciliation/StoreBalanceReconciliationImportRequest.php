<?php

/**
 * Created by VScode.
 * User: sandeep
 * Date: 12/17/2020
 * Time: 11:57 PM
 */

namespace App\Modules\Store\Requests\BalanceReconciliation;


use Illuminate\Foundation\Http\FormRequest;

class StoreBalanceReconciliationImportRequest extends FormRequest
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
            'import_file' => 'required|mimes:xlsx,xls,csv',

        ];
    }

}

