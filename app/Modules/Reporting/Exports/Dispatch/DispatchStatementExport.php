<?php


namespace App\Modules\Reporting\Exports\Dispatch;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DispatchStatementExport implements FromView,ShouldAutoSize
{
    protected $dispatchStatements;
    protected $warehouseName;

    function __construct($dispatchStatements,$warehouseName) {
        $this->dispatchStatements = $dispatchStatements;
        $this->warehouseName = $warehouseName;
    }

    public function view(): View
    {
        return view('Reporting::admin.wh-reporting.excel-bills.dispatch-statements', [
            'dispatchStatements' => $this->dispatchStatements,
            'warehouseName' => $this->warehouseName
        ]);
    }

}
