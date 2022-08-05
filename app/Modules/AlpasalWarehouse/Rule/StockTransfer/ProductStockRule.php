<?php


namespace App\Modules\AlpasalWarehouse\Rule\StockTransfer;

use App\Modules\AlpasalWarehouse\Helpers\WarehouseProductStockHelper;
use Illuminate\Contracts\Validation\Rule;

class ProductStockRule implements Rule
{
    private $warehouseProductCode;

    public function __construct($warehouseProductCode)
    {
        $this->warehouseProductCode = $warehouseProductCode;
    }

    public function passes($attribute, $value)
    {
        $index = explode('.', $attribute)[1];
        $product = WarehouseProductStockHelper::findOrFailCurrentProductStockInWarehouse($this->warehouseProductCode[$index]);

        if ($value > $product->current_stock ){
            return false;
        }
        return true;
    }

    public function message()
    {
        return "The :attribute should be less than or equal to stock of the product";
    }
}