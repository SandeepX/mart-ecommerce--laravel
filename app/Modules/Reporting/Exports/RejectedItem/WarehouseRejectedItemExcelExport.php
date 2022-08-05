<?php


namespace App\Modules\Reporting\Exports\RejectedItem;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WarehouseRejectedItemExcelExport implements FromView, ShouldAutoSize
{
    protected $warehouseRejectedItemData;
    protected $warehouseName;
    protected $module;
    protected $viewPath;


    function __construct($warehouseRejectedItemData,$warehouseName,$module,$viewPath)
    {
        $this->viewPath = $viewPath;
        $this->module = $module;
        $this->warehouseRejectedItemData = $warehouseRejectedItemData;
        $this->warehouseName = $warehouseName;
    }


    public function view(): View
    {

        return view($this->module . $this->viewPath . '.index', [
            'warehouseRejectedItemData' => $this->warehouseRejectedItemData,
            'warehouseName' => $this->warehouseName
        ]);


    }

}



