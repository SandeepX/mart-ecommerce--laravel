<?php


namespace App\Modules\Reporting\Exports\Dispatch;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DispatchRecordsOfProductExport implements FromView,ShouldAutoSize
{
    protected $dispatchStatementProductWise;
    protected $warehouseName;
    protected $storeName;
    protected $productName;
    protected $productVariantName;

    function __construct(
        $dispatchStatementProductWise,
        $warehouseName,
        $storeName,
        $productName,
        $productVariantName=null
    ){
        $this->dispatchStatementProductWise = $dispatchStatementProductWise;
        $this->warehouseName = $warehouseName;
        $this->storeName = $storeName;
        $this->productName = $productName;
        $this->productVariantName = $productVariantName;
    }

    public function view(): View
    {
        return view('Reporting::admin.wh-reporting.excel-bills.dispatch-records-of-product', [
            'dispatchStatementProductWise' => $this->dispatchStatementProductWise,
            'warehouseName' => $this->warehouseName,
            'storeName' => $this->storeName,
            'productName' => $this->productName,
            'productVariantName' => $this->productVariantName,
        ]);
    }

}
