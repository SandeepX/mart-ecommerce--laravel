<?php


namespace App\Modules\Store\Exports;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class StoreBalanceReconciliationExport implements
    FromArray,
    ShouldAutoSize,
    WithHeadings,
    WithEvents,
    WithColumnFormatting
{
    use Exportable;
    protected $failures;

    function __construct($failures) {
        $this->failures = $failures;
    }

    public function array(): array
    {
        $data = [];
        foreach ($this->failures as $failure)
        {
            array_push($data, $failure->values());
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'transaction_type',
            'payment_method',
            'payment_body_code',
            'transaction_no',
            'transaction_amount',
            'transacted_by',
            'description',
            'transaction_date',
            'created_by',
            'updated_by',
            'status'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:K1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                ]);
            }
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
        ];
    }
}
