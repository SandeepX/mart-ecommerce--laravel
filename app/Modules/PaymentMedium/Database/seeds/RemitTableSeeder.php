<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/22/2020
 * Time: 5:30 PM
 */

namespace App\Modules\PaymentMedium\Database\seeds;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemitTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        // CG | Finco
        // Himal Remit
        // IME Nepal
        // MoneyGram Nepal
        // Muncha Money Transfer
        // Prabhu Money Transfer
        // Xpress Money
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('remits')->truncate();
        $remits = [
            [
                'id' =>1,
                'remit_code' => 'RM01',
                'remit_name' => 'CG | Finco',
                'remit_slug' => 'cg-finco',
                'is_active' => 1
            ],
            [
                'id' =>2,
                'remit_code' => 'RM02',
                'remit_name' => 'Himal Remit',
                'remit_slug' => 'himal-remit',
                'is_active' => 1
            ],

            [
                'id' =>3,
                'remit_code' => 'RM03',
                'remit_name' => 'IME Nepal',
                'remit_slug' => 'ime-nepal',
                'is_active' => 1
            ],
            [
                'id' =>4,
                'remit_code' => 'RM04',
                'remit_name' => 'MoneyGram Nepal',
                'remit_slug' => 'money-gram-nepal',
                'is_active' => 1
            ],
            [
                'id' =>5,
                'remit_code' => 'RM05',
                'remit_name' => 'Muncha Money Transfer',
                'remit_slug' => 'muncha-money-transfer',
                'is_active' => 1
            ],
            [
                'id' =>6,
                'remit_code' => 'RM06',
                'remit_name' => 'Prabhu Money Transfer',
                'remit_slug' => 'prabhu-money-transfer',
                'is_active' => 1
            ],
            [
                'id' =>7,
                'remit_code' => 'RM07',
                'remit_name' => 'Xpress Money',
                'remit_slug' => 'xpress-money',
                'is_active' => 1
            ],
        ];

       DB::table('remits')->insert($remits);
    }
}
