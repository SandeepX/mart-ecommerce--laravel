<?php


namespace App\Modules\AlpasalWarehouse\Requests\StockTransfer;

use App\Modules\Product\Helpers\ProductUnitPackagingHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddProductToStockTransferRequest extends FormRequest
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
      //  dd($this->all());
        return [
            'warehouse_product_master_code' => 'required|array',
           // 'warehouse_product_master_code.*' => 'required',
            'warehouse_product_master_code.*' => ['required',Rule::exists('warehouse_product_master', 'warehouse_product_master_code')
                ->where(function ($query) {
                $query->where('warehouse_code', getAuthWarehouseCode());
            })],
            'product_code' => 'required|array',
            'product_code.*' => 'required',
            'product_variant_code' => 'nullable|array',
            'product_variant_code.*' => 'nullable',
            'package_code' => 'required|array',
            'package_code.*'=>'required',Rule::exists('product_packaging_details','package_code')
                  ->where(function($query){
                      $query->where(ProductUnitPackagingHelper::findProductPackagingDetail($this->product_code,$this->product_variant_code));
                  }),
//            'package_codes.*' => 'required',Rule::exists(ProductUnitPackagingHelper::
//            findProductPackagingDetail($this->product_code,$this->product_variant_code)),
            'quantity' => 'required|array',
            'quantity.*' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'warehouse_product_master_codes.required' => 'Warehouse Master code required',
            'warehouse_product_master_codes.*.exists' => 'Invalid Product',
        ];
    }
}
