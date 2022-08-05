<?php


namespace App\Modules\Reporting\Exports\Dispatch;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DispatchRecordsStoreWise implements FromView,ShouldAutoSize
{
    protected $dispatchRecordsStoreWise;
    protected $warehouseName;
    protected $productName;
    protected $productVariantName;

    function __construct(
        $dispatchRecordsStoreWise,
        $warehouseName,
        $productName,
        $productVariantName=null
    ){
        $this->dispatchRecordsStoreWise = $dispatchRecordsStoreWise;
        $this->warehouseName = $warehouseName;
        $this->productName = $productName;
        $this->productVariantName = $productVariantName;
    }

    public function view(): View
    {
        return view('Reporting::admin.wh-reporting.excel-bills.store-wise-dispatch-records', [
            'dispatchRecordsStoreWise' => $this->dispatchRecordsStoreWise,
            'warehouseName' => $this->warehouseName,
            'productName' => $this->productName,
            'productVariantName' => $this->productVariantName
        ]);
    }

}
