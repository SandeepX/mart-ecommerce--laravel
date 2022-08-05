<?php


namespace App\Modules\Reporting\Exports\Stock;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockStatementDetailExport implements FromView,ShouldAutoSize
{
    protected $stockStatementDetails;
    protected $warehouseProductMaster;

    function __construct($stockStatementDetails,$warehouseProductMaster) {
        $this->stockStatementDetails = $stockStatementDetails;
        $this->warehouseProductMaster = $warehouseProductMaster;
    }

    public function view(): View
    {
        return view('Reporting::admin.wh-stock-reporting.excel-bills.stock-statement-details-excel', [
            'warehouseProductStatements' => $this->stockStatementDetails,
            'warehouseProductMaster' => $this->warehouseProductMaster
        ]);
    }

}
