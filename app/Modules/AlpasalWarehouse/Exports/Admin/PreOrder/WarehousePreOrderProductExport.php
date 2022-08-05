<?php


namespace App\Modules\AlpasalWarehouse\Exports\Admin\PreOrder;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Excel;

class WarehousePreOrderProductExport implements
    FromView,
    ShouldAutoSize,
    WithEvents,
    Responsable
{
    use Exportable;
    private $warehousePreOrderListing;
    private $storePreOrderProducts;
    private $vendors;
    private $module;
    private $viewPath;
    private $fileName;
    private $writerType = Excel::XLSX;
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    private $maxNumOfHeadingGroups;

    public function __construct($warehousePreOrderListing, $storePreOrderProducts, $vendors, $module, $viewPath)
    {
        $this->warehousePreOrderListing = $warehousePreOrderListing;
        $this->storePreOrderProducts = $storePreOrderProducts;
        $this->vendors = $vendors;
        $this->module = $module;
        $this->viewPath = $viewPath;
        $this->fileName = Str::snake($this->warehousePreOrderListing->warehouse->warehouse_name)
            .'(wh)_'
            .Str::snake($this->vendors->vendor_name)
            .'(vendor)_'
            .getReadableDate($this->warehousePreOrderListing->start_time, 'j-M-Y')
            .'(start_time)_'
            .getReadableDate($this->warehousePreOrderListing->end_time, 'j-M-Y')
            .'(end_time)_'
            .getReadableDate($this->warehousePreOrderListing->finalization_time, 'j-M-Y')
            .'(finalization_time)_'
            .'warehouse_pre_order_products.xlsx';

        $this->getMaxNumOfHeadingGroups();
    }

    public function view(): View
    {

        return view($this->module.$this->viewPath.'exports.warehouse_pre_order_product_export_new', [
            'storePreOrderProducts' => $this->storePreOrderProducts,
            'vendors' => $this->vendors,
            'warehousePreOrderListing' => $this->warehousePreOrderListing,
            'maxNumOfHeadingGroups'=>$this->maxNumOfHeadingGroups
        ]);
    }

    private function getMaxNumOfHeadingGroups(){
        $productPackagingsArray =array_column($this->storePreOrderProducts->toArray(),'product_packagings_price');
        $this->maxNumOfHeadingGroups=count(max($productPackagingsArray));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $last_column = Coordinate::stringFromColumnIndex(5);
                $last_row = $this->storePreOrderProducts->count() + 7 + 1 + 2;
                $style_text_center = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                    'font' => [
                        'bold' => true
                    ]
                ];
                $event->sheet->insertNewRowBefore(1, 7);



                $event->sheet->mergeCells(sprintf('A1:%s1', $last_column));
                $event->sheet->mergeCells(sprintf('A2:%s2', $last_column));
                $event->sheet->mergeCells(sprintf('A3:%s3', $last_column));
                $event->sheet->mergeCells(sprintf('A4:%s4', $last_column));
                $event->sheet->mergeCells(sprintf('A5:%s5', $last_column));
                $event->sheet->mergeCells(sprintf('A6:%s6', $last_column));
                $event->sheet->mergeCells(sprintf('A7:%s7', $last_column));

                $event->sheet->setCellValue('A1', 'Warehouse: '.$this->warehousePreOrderListing->warehouse->warehouse_name);
                $event->sheet->setCellValue('A2', 'Vendor: '.$this->vendors->vendor_name);
                $event->sheet->setCellValue('A3', 'Start Date: '.$this->warehousePreOrderListing->start_time);
                $event->sheet->setCellValue('A4', 'End Date: '.$this->warehousePreOrderListing->end_time);
                $event->sheet->setCellValue('A5', 'Finalization Date: '.$this->warehousePreOrderListing->finalization_time);
                $event->sheet->setCellValue('A6', 'File Generated Time: '.getReadableDate(getNepTimeZoneDateTime(\Carbon\Carbon::now())));
                $event->sheet->setCellValue('A7', '');
                $event->sheet->setCellValue('B'.$last_row, 'Total');
               // $event->sheet->setCellValue('D'.$last_row, $this->storePreOrderProducts->sum('total_ordered_quantity'));
                //$event->sheet->setCellValue('F'.$last_row, $this->storePreOrderProducts->sum('sub_total'));

                $priceColumns =['F','J','N','R'];

                $productPackagingsArray =array_column($this->storePreOrderProducts->toArray(),'product_packagings_price');

                $prices=[];
                for($i=0;$i<$this->maxNumOfHeadingGroups;$i++){
                    $priceValue=0;
                    foreach($productPackagingsArray as $key=>$productPackaging){
                        $priceValue +=isset($productPackaging[$i])?
                            $productPackaging[$i]['product_package_price']: 0;
                        $prices[$i]= $priceValue;
                    }
                }
                foreach ($prices as $key=>$price){
                    $event->sheet->setCellValue($priceColumns[$key].$last_row, $price);
                }

                //dd($price);


                $event->sheet->getStyle('A1:A6')->applyFromArray($style_text_center);
                $event->sheet->getStyle('B'.$last_row.':E'.$last_row)->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                ]);
            }
        ];
    }
}
