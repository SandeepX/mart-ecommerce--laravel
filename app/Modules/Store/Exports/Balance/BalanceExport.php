<?php


namespace App\Modules\Store\Exports\Balance;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\BeforeExport;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Excel;

class BalanceExport implements
    FromCollection,
    Responsable,
    ShouldAutoSize,
    WithHeadings,
    WithEvents,
    WithMapping,
    WithColumnFormatting
{
    use Exportable;

    private $storeBalances;
    private $n = 1;
    private $fileName;
    private $writerType = Excel::XLSX;
    private $headers = [
        'Content-Type' => 'text/csv',
    ];


    public function __construct($storeBalances)
    {
        $this->storeBalances = $storeBalances;
        $this->fileName = getReadableDate(now(), 'j-M-Y').'_store_balances.xlsx';
    }

    public function collection()
    {
        return $this->storeBalances;
    }

    public function headings(): array
    {
        return [
            'S.N',
            'Store Name',
            'Last Transaction',
            'Current Balance'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $last_column = Coordinate::stringFromColumnIndex(4);

                $style_text_center = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                    'font' => [
                        'bold' => true
                    ]
                ];
                // at row 1, insert 2 rows
                $event->sheet->insertNewRowBefore(1);

                $event->sheet->mergeCells(sprintf('A1:%s1',$last_column));
                $event->sheet->setCellValue('A1','Title: List of Store Balances');
                $event->sheet->getStyle('A1')->applyFromArray($style_text_center);
                $event->sheet->getStyle('A2:D2')->applyFromArray([
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
            $row->store_name.'('.$row->store_code.')',
            getReadableDate($row->last_transaction_date, 'j-M-Y'),
            'Rs. '.$row->store_current_balance
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}