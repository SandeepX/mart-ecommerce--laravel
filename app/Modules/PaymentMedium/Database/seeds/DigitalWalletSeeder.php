<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/22/2020
 * Time: 5:34 PM
 */

namespace App\Modules\PaymentMedium\Database\seeds;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DigitalWalletSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//         npay
// IME pay
// epay
// Payway
// Qpay
// Ipay
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('digital_wallets')->truncate();
        $paymentGateways = [
            [
                'id' =>1,
                'wallet_code' => 'DW01',
                'wallet_name' => 'Esewa',
                'wallet_slug' => 'esewa',
                'is_active' => 1
            ],
            [
                'id' =>2,
                'wallet_code' => 'DW02',
                'wallet_name' => 'Khalti',
                'wallet_slug' => 'khalti',
                'is_active' => 1
            ],
            [
                'id' =>3,
                'wallet_code' => 'DW03',
                'wallet_name' => 'IME Pay',
                'wallet_slug' => 'ime-pay',
                'is_active' => 1
            ],
            [
                'id' =>4,
                'wallet_code' => 'DW04',
                'wallet_name' => 'nPay',
                'wallet_slug' => 'npay',
                'is_active' => 1
            ],
            [
                'id' =>5,
                'wallet_code' => 'DW05',
                'wallet_name' => 'QPay',
                'wallet_slug' => 'qpay',
                'is_active' => 1
            ],
            [
                'id' =>6,
                'wallet_code' => 'DW06',
                'wallet_name' => 'Connect Ips',
                'wallet_slug' => 'connect-ips',
                'is_active' => 1
            ]

        ];

        DB::table('digital_wallets')->insert($paymentGateways);
    }
}
