<?php
namespace App\Modules\Accounting\Database\seeds;

use App\Modules\Accounting\Models\FiscalYear;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountingFiscalYearCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FiscalYear::create([
            'fiscal_year_name' => '2077/78',
            'is_closed' => 0,
            'created_by' =>'U00000001',
            'updated_by' =>'U00000001'
        ]);
    }
}
