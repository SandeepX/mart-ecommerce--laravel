<?php


namespace App\Modules\AlpasalWarehouse\Requests\StockTransfer;

use Illuminate\Foundation\Http\FormRequest;

class StockTransferAddDeliveryDetailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'key' => 'required|string',
            'value' => '',
            'file' => 'mimes:jpeg,jpg,png,txt,pdf,doc,docx'
        ];
    }
}