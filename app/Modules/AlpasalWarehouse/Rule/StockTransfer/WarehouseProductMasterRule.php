<?php


namespace App\Modules\AlpasalWarehouse\Rule\StockTransfer;


use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class WarehouseProductMasterRule implements Rule
{
    private $productCode;
    private $productVariantCode;
    private $vendorCode;

    public function __construct($productCode, $productVariantCode, $vendorCode)
    {
        $this->productCode = $productCode;
        $this->productVariantCode = $productVariantCode;
        $this->vendorCode = $vendorCode;
    }

    public function passes($attribute, $value)
    {
        $index = explode('.', $attribute)[1];
        $product = DB::table('warehouse_product_master')
            ->where('warehouse_product_master_code', $value)
            ->where('warehouse_code', '!=', getAuthWarehouseCode())
            ->where('product_code', $this->productCode[$index])
            ->where('product_variant_code', $this->productVariantCode[$index])
            ->where('vendor_code', $this->vendorCode[$index])
            ->where('is_active', 1)
            ->get();

        if (!isset($product)){
            return false;
        }
        return true;
    }

    public function message()
    {
        return "The :attribute is incorrect!";
    }
}