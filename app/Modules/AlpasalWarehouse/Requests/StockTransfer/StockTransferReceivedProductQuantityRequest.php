<?php


namespace App\Modules\AlpasalWarehouse\Requests\StockTransfer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StockTransferReceivedProductQuantityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
//        $this->customRules();
        return [
            'warehouse_product_master_code' => 'required|array',
            'warehouse_product_master_code.*' => ['required',Rule::exists('warehouse_product_master','warehouse_product_master_code')],
            /* 'received_quantity' => 'required|array|min:1',
             'received_quantity.*' => 'nullable|integer|min:1',*/

            //'micro_received_quantity' => 'required_without:unit_received_quantity,macro_received_quantity,super_received_quantity|array',
            'micro_received_quantity' => 'nullable|array',
            'micro_received_quantity.*' => 'nullable|required_without_all:unit_received_quantity.*,macro_received_quantity.*,super_received_quantity.*|integer|min:0',

            // 'unit_received_quantity' => 'required_without:micro_received_quantity,macro_received_quantity,super_received_quantity|array',
            'unit_received_quantity' => 'nullable|array',
            'unit_received_quantity.*' => 'nullable|required_without_all:micro_received_quantity.*,macro_received_quantity.*,super_received_quantity.*|integer|min:0',

            // 'macro_received_quantity' => 'required_without:micro_received_quantity,unit_received_quantity,super_received_quantity|array|min:1',
            'macro_received_quantity' => 'nullable|array',
            'macro_received_quantity.*' => 'nullable|required_without_all:micro_received_quantity.*,unit_received_quantity.*,super_received_quantity.*|integer|min:0',

            // 'super_received_quantity' => 'required_without:micro_received_quantity,unit_received_quantity,macro_received_quantity|array|min:1',
            'super_received_quantity' => 'nullable|array',
            'super_received_quantity.*' => 'nullable|required_without_all:micro_received_quantity.*,macro_received_quantity.*,unit_received_quantity.*|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'stock_transfer_details_code.stock_transfer_details_validation' => 'Invalid stock detail code!',
            'product_code.product_code_validation' => 'Choose correct product!',
//            'product_variant_code.product_variant_code_validation' => 'Choose correct product variant!',
        ];
    }

//    public function customRules()
//    {
//        $productMaster = new ProductMaster();
//        $stockTransferDetail = new WarehouseStockTransferDetail();
////        $variantCode = new ProductVariant();
//        Validator::extend('productCodeValidation', function ($attribute, $value, $parameters, $validator) use($productMaster) {
//            $product = $productMaster->where('product_code', $value);
//            if (!isset( $product )) {
//                return false;
//            }
//            return true;
//        });
//
//        Validator::extend('stockTransferDetailsValidation', function ($attribute, $value, $parameters, $validator) use ($stockTransferDetail) {
//            $stockTransferDetail = $stockTransferDetail->where('stock_transfer_details_code', $value)->firstOrFail();
//            if (!isset( $stockTransferDetail )) {
//                return false;
//            }
//            return true;
//        });
//
////        Validator::extend('productVariantCodeValidation', function ($attribute, $value, $parameters, $validator) use ($variantCode) {
////            $variant = $variantCode->where('product_variant_code', $value)->firstOrFail();
////            if (!isset( $stockTransferDetail )) {
////                return false;
////            }
////            return true;
////        });
//    }
}
