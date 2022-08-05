<?php


namespace App\Modules\InventoryManagement\Exports\InventorySales;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StoreInventoryCurrentStockRecordExport implements FromView, ShouldAutoSize
{
    protected $storeCurrentStockDetail;

    function __construct($storeCurrentStockDetail)
    {
        $this->storeCurrentStockDetail = $storeCurrentStockDetail;
    }

    public function view(): View
    {
        return view('InventoryManagement::record-export.inventory-current-stock-record', [
            'storeCurrentStockDetail' => $this->storeCurrentStockDetail,
        ]);
    }

}


