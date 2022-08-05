<?php


namespace App\Modules\AlpasalWarehouse\Exports\Warehouse;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Str;

class WarehousePurchasePreOrderExport implements
    FromCollection,
    Responsable,
    ShouldAutoSize,
    WithHeadings,
    WithEvents,
    WithMapping,
    WithColumnFormatting
{
    use Exportable;

    private $storePreOrderProducts;
    private $warehouseName;
    private $n = 1;
    private $fileName;
    private $writerType = Excel::XLSX;
    private $headers = [
        'Content-Type' => 'text/csv',
    ];
    private $preOrderDate;
    private $vendor;


    public function __construct($storePreOrderProducts, $warehouseName)
    {
        $this->storePreOrderProducts = $storePreOrderProducts;
        $this->warehouseName = $warehouseName;
        $this->preOrderDate = getReadableDate($this->storePreOrderProducts[0]->start_time, 'j-M-Y')
            .'_'
            .getReadableDate($this->storePreOrderProducts[0]->end_time, 'j-M-Y');
        $this->vendor = Str::snake($this->storePreOrderProducts[0]->vendor_name);
        $this->fileName = Str::snake($this->warehouseName).'(wh)_'.$this->vendor.'(vendor)_'.$this->preOrderDate.'_purchase_pre_order.xlsx';
    }

    public function collection()
    {
        return $this->storePreOrderProducts;
    }

    public function headings(): array
    {
        return [
            'S.N',
            'Warehouse',
            'Vendor',
            'Product',
            'Pre Order Date',
            'Estimated Quantity'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
            $event->sheet->getStyle('A1:F1')->applyFromArray([
                'font' => [
                    'bold' => true
                ],
            ]);
            }
        ];
    }

    public function map($row): array
    {

        return [
            $this->n++,
            $this->warehouseName,
            $row->vendor_name,
            isset($row->product_variant_name) ?
                $row->product_name.'('.$row->product_variant_name.')' :
                $row->product_name,
            getReadableDate($row->start_time, 'j-M-Y').'/'.getReadableDate($row->end_time, 'j-M-Y'),
            $row->total_ordered_quantity
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}