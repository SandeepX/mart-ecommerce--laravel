<?php


namespace App\Modules\Reporting\Exports\DemandProjection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DemandProjectionExport implements FromView,ShouldAutoSize
{
    protected $demandProjection;
    protected $warehouseName;

    function __construct($demandProjection,$warehouseName) {
        $this->demandProjection = $demandProjection;
        $this->warehouseName = $warehouseName;
    }

    public function view(): View
    {
        return view('Reporting::admin.wh-demand-projection.excel-bills.demand-projection-excel', [
            'demandProjection' => $this->demandProjection,
            'warehouseName' => $this->warehouseName
        ]);
    }

}
