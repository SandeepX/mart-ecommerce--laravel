<?php


namespace App\Modules\AlpasalWarehouse\Rule\StockTransfer;

use App\Modules\AlpasalWarehouse\Models\StockTransfer\WarehouseStockTransferDetail;
use Illuminate\Contracts\Validation\Rule;

class ReceivedProductQuantityRule implements Rule
{
    private $stockTransferDetailsCode;

    public function __construct($stockTransferDetailsCode)
    {
        $this->stockTransferDetailsCode = $stockTransferDetailsCode;
    }

    public function passes($attribute, $value)
    {
        $index = explode('.', $attribute)[1];
        $stockTransferDetail = WarehouseStockTransferDetail::where('stock_transfer_details_code', $this->stockTransferDetailsCode[$index])->firstOrFail();

        if ($value > $stockTransferDetail->sending_quantity ){
            return false;
        }
        return true;
    }

    public function message()
    {
        return "The :attribute should be less than sending quantity";
    }
}