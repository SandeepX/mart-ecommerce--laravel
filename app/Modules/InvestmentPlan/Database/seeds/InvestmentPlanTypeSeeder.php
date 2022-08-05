<?php


namespace App\Modules\InvestmentPlan\Database\seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class InvestmentPlanTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('investment_plan_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $investmentTypes = [
            [
                'ip_type_code' => 'IPT1000',
                'name' => 'interest',
                'slug' => 'interest',
                'description' => 'interest amount will be return at the time of maturity period,no shares ',
                'is_active' => 1,
                'created_by' => 'U00000001',
                'updated_by' => 'U00000001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [
                'ip_type_code' => 'IPT1001',
                'name' => 'interest share',
                'slug' => 'interest-share',
                'description' => 'Principle amount will be return at the time of maturity period and only interest amount is converted to unit of shares',
                'is_active' => 1,
                'created_by' => 'U00000001',
                'updated_by' => 'U00000001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [
                'ip_type_code' => 'IPT1002',
                'name' => 'principle share',
                'slug' => 'principle-share',
                'description' => 'Interest  amount will be return at the time of maturity period and only Principal amount is converted to unit of shares',
                'is_active' => 1,
                'created_by' => 'U00000001',
                'updated_by' => 'U00000001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

        ];

        DB::table('investment_plan_types')->insert($investmentTypes);
    }
}
