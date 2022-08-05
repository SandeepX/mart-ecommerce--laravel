<?php


namespace App\Modules\Reporting\Exports\RejectedItem;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WarehouseRejectedItemDaybookExcelExport implements FromView, ShouldAutoSize
{
    protected $rejectedItemStatement;
    protected $warehouseName;
    protected $module;
    protected $viewPath;

    function __construct($rejectedItemStatement,$module, $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->module = $module;
        $this->rejectedItemStatement = $rejectedItemStatement;
    }

    public function view(): View
    {
        return view($this->module . $this->viewPath . '.rejected-item-daybook-export', [
            'rejectedItemStatement' => $this->rejectedItemStatement

        ]);

    }

}




