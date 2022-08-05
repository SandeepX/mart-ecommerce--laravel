<?php


namespace App\Modules\AlpasalWarehouse\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StorePreOrderExport implements FromView,ShouldAutoSize
{
    protected $storePreOrderProducts;

    function __construct($storePreOrderProducts) {
        $this->storePreOrderProducts = $storePreOrderProducts;
    }

    public function view(): View
    {

        return view('AlpasalWarehouse::warehouse.warehouse-pre-orders.store-pre-orders.bills.excel-bill', [
            'storePreOrderProducts' => $this->storePreOrderProducts,
            'total_order_price' => $this->storePreOrderProducts->sum('tax_sub_total')
        ]);
    }

}
