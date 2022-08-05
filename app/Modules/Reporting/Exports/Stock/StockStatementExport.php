<?php


namespace App\Modules\Reporting\Exports\Stock;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockStatementExport implements FromView,ShouldAutoSize
{
    protected $stockStatements;
    protected $warehouseName;

    function __construct($stockStatements,$warehouseName) {
        $this->stockStatements = $stockStatements;
        $this->warehouseName = $warehouseName;
    }

    public function view(): View
    {
        return view('Reporting::admin.wh-stock-reporting.excel-bills.stock-statement-excel', [
            'warehouseProductStatements' => $this->stockStatements,
            'warehouseName' => $this->warehouseName
        ]);
    }

}
