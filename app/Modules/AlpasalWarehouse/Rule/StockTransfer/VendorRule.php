<?php


namespace App\Modules\AlpasalWarehouse\Rule\StockTransfer;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class VendorRule implements Rule
{
    private $productCode;
    private $productVariantCode;

    public function __construct($productCode, $productVariantCode)
    {
        $this->productCode = $productCode;
        $this->productVariantCode = $productVariantCode;
    }

    public function passes($attribute, $value)
    {
        $index = explode('.', $attribute)[1];
        $product = DB::table('vendor_product_price_view')
            ->where('product_code', $this->productCode[$index])
            ->where('vendor_code', $value)
            ->orWhere('vendor_product_price_view.product_variant_code', $this->productVariantCode[$index])
            ->get();

        if (!isset($product)){
            return false;
        }
        return true;
    }

    public function message()
    {
        return "Please enter correct information!";
    }
}