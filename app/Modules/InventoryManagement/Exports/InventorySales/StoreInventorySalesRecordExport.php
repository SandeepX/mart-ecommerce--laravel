<?php


namespace App\Modules\InventoryManagement\Exports\InventorySales;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StoreInventorySalesRecordExport implements FromView, ShouldAutoSize
{
    protected $storeInventoryStockDispatchedDetail;

    function __construct($storeInventoryStockDispatchedDetail)
    {
        $this->storeInventoryStockDispatchedDetail = $storeInventoryStockDispatchedDetail;
    }

    public function view(): View
    {
        return view('InventoryManagement::record-export.inventory-sales-record', [
            'storeInventoryStockDispatchedDetail' => $this->storeInventoryStockDispatchedDetail,
        ]);
    }

}

