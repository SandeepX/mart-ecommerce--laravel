<?php


namespace App\Modules\Wallet\Exports;

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

class StoreWalletDetailExcelExport implements
    FromView,
    ShouldAutoSize,
    WithEvents,
    Responsable
{
    use Exportable;

    private $module;
    private $viewPath;

    private $wallet;
    private $activeBalance;
    private $frozenBalanceDetails;
    private $fileName;
    private $writerType = Excel::XLSX;
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    private $maxNumOfHeadingGroups;
    private $allTransactionByWalletCode;

    public function __construct($allTransactionByWalletCode,$wallet,$activeBalance,$frozenBalanceDetails, $module, $viewPath)
    {
        $this->module = $module;
        $this->viewPath = $viewPath;
        $this->wallet = $wallet;
        $this->activeBalance = $activeBalance;
        $this->frozenBalanceDetails = $frozenBalanceDetails;
        $this->allTransactionByWalletCode = $allTransactionByWalletCode;

        $this->fileName = 'store_wallet_detail_export.xlsx';
        $this->getMaxNumOfHeadingGroups();
    }

    public function view(): View
    {
        return view($this->module . $this->viewPath . '.exports.wallet_detail_export', [
            'allTransactionByWalletCode' => $this->allTransactionByWalletCode,
            'maxNumOfHeadingGroups' => $this->maxNumOfHeadingGroups
        ]);
    }

    private function getMaxNumOfHeadingGroups()
    {
        $allTransactionByWalletCodeArray = array_column($this->allTransactionByWalletCode->toArray(), 'transaction_code');
        $this->maxNumOfHeadingGroups = count($allTransactionByWalletCodeArray);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $last_column = Coordinate::stringFromColumnIndex(8);
                $last_row = $this->allTransactionByWalletCode->count() + 7 + 1 + 2;
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

                $event->sheet->setCellValue('A2', 'Allpasal Store All Wallet Transaction Detail');
                $event->sheet->setCellValue('A3', 'Transactions of Store: '.$this->wallet->holder_name .'/'. $this->wallet->wallet_holder_code .'| Total Balance : Current Balance: '.getNumberFormattedAmount($this->wallet->current_balance));
                $event->sheet->setCellValue('A4', 'Active Balance of Store: '.getNumberFormattedAmount($this->activeBalance));
                $event->sheet->setCellValue('A5', 'Frozen Balance of Store: '.getNumberFormattedAmount($this->frozenBalanceDetails['total_freeze_amount']) . '| Withdraw Freeze: '.getNumberFormattedAmount($this->frozenBalanceDetails['total_withdraw_freeze']).'| Preorder Freeze: '. getNumberFormattedAmount($this->frozenBalanceDetails['total_preorder_freeze']));
                $event->sheet->setCellValue('A6', 'File Generated Time: ' . getReadableDate(getNepTimeZoneDateTime(\Carbon\Carbon::now())));
                $event->sheet->setCellValue('A7', '');

                $event->sheet->getStyle('A1:A6')->applyFromArray($style_text_center);
                $event->sheet->getStyle('B' . $last_row . ':E' . $last_row)->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                ]);
            }
        ];
    }
}

