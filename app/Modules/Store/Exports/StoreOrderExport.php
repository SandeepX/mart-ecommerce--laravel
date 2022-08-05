<?php


namespace App\Modules\Store\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StoreOrderExport implements FromView,ShouldAutoSize
{
    protected $storeOrders;

    function __construct($storeOrders) {
        $this->storeOrders = $storeOrders;
    }

    public function view(): View
    {

        return view('Store::admin.store-order.store-order-excel-bill', [
            'storeOrders' => $this->storeOrders,
        ]);
    }

}
