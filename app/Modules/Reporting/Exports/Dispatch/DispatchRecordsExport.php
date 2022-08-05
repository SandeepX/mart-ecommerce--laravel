<?php


namespace App\Modules\Reporting\Exports\Dispatch;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DispatchRecordsExport implements FromView,ShouldAutoSize
{
    protected $dispatchRecords;
    protected $warehouseName;

    function __construct($dispatchRecords,$warehouseName) {
        $this->dispatchRecords = $dispatchRecords;
        $this->warehouseName = $warehouseName;
    }

    public function view(): View
    {

        return view('Reporting::admin.wh-reporting.excel-bills.dispatch-records', [
            'dispatchRecords' => $this->dispatchRecords,
            'warehouseName' => $this->warehouseName
        ]);
    }

}
