<?php


namespace App\Modules\AlpasalWarehouse\Exports;


use Barryvdh\DomPDF\Facade as PDF;

class StorePreOrderPdfExport
{

    protected $orderInfo,$storePreOrderDetails;
    protected $pdfViewPath='AlpasalWarehouse::warehouse.warehouse-pre-orders.store-pre-orders.bills.pdf-bill';
    protected $outputFileName='allpasal_store_preorder.pdf';

    function __construct($orderInfo,$storePreOrderDetails) {
        $this->orderInfo = $orderInfo;
        $this->storePreOrderDetails = $storePreOrderDetails;

        $this->setOutputFileName($this->outputFileName);
    }

    public function setPdfViewPath($pdfViewPath){
        $this->pdfViewPath = $pdfViewPath;
    }

    public function setOutputFileName($outputFileName){
        $outputFileName=time().'-'.$this->orderInfo['invoice_num'].'-'.$outputFileName;
        $this->outputFileName = $outputFileName;
    }

    public function export($outputType='stream'){
        $orderInfo = $this->orderInfo;
        $storePreOrderDetailsWithChunk = $this->storePreOrderDetails;
        //$this->setOutputFileName($this->outputFileName);
        $pdf = PDF::loadView($this->pdfViewPath, compact('orderInfo','storePreOrderDetailsWithChunk'));

        if ($outputType == 'download'){
            return $pdf->download($this->outputFileName);
        }
        elseif ($outputType == 'output'){
            return $pdf->output();
        }
        return $pdf->stream($this->outputFileName);

    }
}
