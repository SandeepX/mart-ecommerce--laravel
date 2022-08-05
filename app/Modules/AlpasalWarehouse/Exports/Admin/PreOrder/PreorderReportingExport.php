<?php


namespace App\Modules\AlpasalWarehouse\Exports\Admin\PreOrder;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PreorderReportingExport implements FromView,ShouldAutoSize
{
    protected $warehousePreOrders;

    function __construct($warehousePreOrders) {
        $this->warehousePreOrders = $warehousePreOrders;
    }

    public function view(): View
    {

        return view('AlpasalWarehouse::admin.reporting.excell-export-reporting', [
            'warehousePreOrders' => $this->warehousePreOrders
        ]);
    }

}
