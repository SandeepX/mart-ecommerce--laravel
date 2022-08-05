<?php

namespace App\Modules\Store\Imports;

use App\Modules\Store\Models\BalanceReconciliation\StoreBalanceReconciliation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class StoreBalanceReconciliationImport implements
    ToModel,
    WithHeadingRow,
    SkipsOnError,
    WithValidation,
    SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        return new StoreBalanceReconciliation([
            'transaction_type' => $row['transaction_type'],
            'payment_method' => $row['payment_method'],
            'payment_body_code' => $row['payment_body_code'],
            'transaction_no' => $row['transaction_no'],
            'transaction_amount' => $row['transaction_amount'],
            'transacted_by' => $row['transacted_by'],
            'description' => $row['description'],
            'transaction_date' => $row['transaction_date'],
            'created_by' => $row['created_by'],
            'updated_by' => $row['updated_by'],
            'status' => $row['status'],
        ]);
    }

    public function rules(): array
    {
        return [
            'transaction_no' => 'unique:balance_reconciliation,transaction_no',
        ];
    }
}
