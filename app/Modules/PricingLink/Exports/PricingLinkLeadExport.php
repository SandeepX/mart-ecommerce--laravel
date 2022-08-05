<?php


namespace App\Modules\PricingLink\Exports;

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

class PricingLinkLeadExport implements
    FromView,
    ShouldAutoSize,
    WithEvents,
    Responsable
{
    use Exportable;
    private $module;
    private $viewPath;
    private $fileName;
    private $writerType = Excel::XLSX;
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    private $maxNumOfHeadingGroups;
    private $pricingLinkLeads;

    public function __construct($pricingLinkLeads, $module, $viewPath)
    {
        $this->module = $module;
        $this->viewPath = $viewPath;
        $this->fileName = 'pricing_link_leads.xlsx';

        $this->pricingLinkLeads=$pricingLinkLeads;
        $this->getMaxNumOfHeadingGroups();
    }

    public function view(): View
    {
        return view($this->module.$this->viewPath.'exports.pricing-link-lead-export', [
            'pricingLinkLeads' => $this->pricingLinkLeads,
            'maxNumOfHeadingGroups'=>$this->maxNumOfHeadingGroups
        ]);
    }

    private function getMaxNumOfHeadingGroups(){
        $pricingLinkLeadsArray =array_column($this->pricingLinkLeads->toArray(),'pricing_master_code');
        $this->maxNumOfHeadingGroups=count($pricingLinkLeadsArray);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $last_column = Coordinate::stringFromColumnIndex(5);
                $last_row = $this->pricingLinkLeads->count() + 7 + 1 + 2;
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

                $event->sheet->setCellValue('A2', 'Pricing Link Leads');
                $event->sheet->setCellValue('A6', 'File Generated Time: '.getReadableDate(getNepTimeZoneDateTime(\Carbon\Carbon::now())));
                $event->sheet->setCellValue('A7', '');

                $priceColumns =['F','J','N','R'];

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
