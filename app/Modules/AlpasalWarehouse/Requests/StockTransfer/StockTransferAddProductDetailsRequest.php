<?php


namespace App\Modules\AlpasalWarehouse\Requests\StockTransfer;

use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseStockTransferDetail;
use App\Modules\AlpasalWarehouse\Repositories\WarehouseProductMasterRepository;
use App\Modules\AlpasalWarehouse\Rule\StockTransfer\ProductStockRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class StockTransferAddProductDetailsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $this->customRules();
        return [
            'stock_transfer_details_code' => 'nullable|array',
            'stock_transfer_details_code.*' => 'nullable|string|distinct|stock_transfer_details_validation',
            'warehouse_product_master_code' => 'required|array',
            'warehouse_product_master_code.*' => 'required|string|distinct|warehouse_product_code_validation',
            'product_quantity' => 'required|array',
            'product_quantity.*' => ['required', 'numeric', 'min:1', new ProductStockRule($this->warehouse_product_master_code)],
        ];
    }

    public function messages()
    {
        return [
            'stock_transfer_details_code.stock_transfer_details_validation' => 'Invalid stock detail code!',
            'warehouse_product_master_code.warehouse_product_code_validation' => 'Choose correct product!',
        ];
    }

    public function customRules()
    {
        $warehouseProductMasterRepository = new WarehouseProductMasterRepository();
        $stockTransferDetail = new WarehouseStockTransferDetail();
        Validator::extend('warehouseProductCodeValidation', function ($attribute, $value, $parameters, $validator) use($warehouseProductMasterRepository) {
            $warehouse_product = $warehouseProductMasterRepository->findOrFailProductByCode($value, getAuthWarehouseCode());
            if (!isset( $warehouse_product )) {
                return false;
            }
            return true;
        });

        Validator::extend('stockTransferDetailsValidation', function ($attribute, $value, $parameters, $validator) use ($stockTransferDetail) {
            $stockTransferDetail = $stockTransferDetail->where('stock_transfer_details_code', $value)->firstOrFail();
            if (!isset( $stockTransferDetail )) {
                return false;
            }
            return true;
        });
    }
}