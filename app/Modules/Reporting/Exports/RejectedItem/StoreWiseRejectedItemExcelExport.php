<?php


namespace App\Modules\Reporting\Exports\RejectedItem;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StoreWiseRejectedItemExcelExport implements FromView, ShouldAutoSize
{
    protected $rejectedItemReportStoreWise;
    protected $ProductName;
    protected $warehouseName;
    protected $productName;
    protected $productVariantName;
    protected $module;
    protected $viewPath;


    function __construct($rejectedItemReportStoreWise,$warehouseName,$productName,$productVariantName,$module, $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->module = $module;
        $this->rejectedItemReportStoreWise = $rejectedItemReportStoreWise;
        $this->warehouseName = $warehouseName;
        $this->productName = $productName;
        $this->productVariantName = $productVariantName;
    }


    public function view(): View
    {
        return view($this->module . $this->viewPath . '.store-wise-rejected-item', [
            'rejectedItemStoreWise' => $this->rejectedItemReportStoreWise,
            'warehouseName' => $this->warehouseName,
            'productName'=>$this->productName,
            'productVariantName'=>$this->productVariantName
        ]);


    }

}



