<?php


namespace App\Modules\Reporting\Exports\RejectedItem;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WarehouseProductWiseItemExcelExport implements FromView, ShouldAutoSize
{
    protected $rejectedItemReportProductWise;
    protected $ProductName;
    protected $warehouseName;
    protected $storeName;
    protected $productName;
    protected $productVariantName;
    protected $module;
    protected $viewPath;


    function __construct($rejectedItemReportProductWise,
                         $warehouseName,
                         $storeName,
                         $productName,
                         $productVariantName,
                         $module,
                         $viewPath
    )
    {
        $this->viewPath = $viewPath;
        $this->module = $module;
        $this->rejectedItemReportProductWise = $rejectedItemReportProductWise;
        $this->warehouseName = $warehouseName;
        $this->storeName = $storeName;
        $this->productName = $productName;
        $this->productVariantName = $productVariantName;
    }


    public function view(): View
    {
        return view($this->module . $this->viewPath . '.product-wise-export', [
            'rejectedItemReportProductWise' => $this->rejectedItemReportProductWise,
            'warehouseName' => $this->warehouseName,
            'storeName' => $this->storeName,
            'productName' => $this->productName,
            'productVariantName' => $this->productVariantName
        ]);


    }

}




