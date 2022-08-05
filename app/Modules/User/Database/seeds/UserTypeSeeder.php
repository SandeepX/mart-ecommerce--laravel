<?php

namespace  App\Modules\User\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $userTypes = [
            [
                'user_type_code' => 'UT001',
                'user_type_name' => 'Super Admin',
                'slug' => 'super-admin',
                'is_active' => 1
            ],
            [
                'user_type_code' => 'UT002',
                'user_type_name' => 'Admin',
                'slug' => 'admin',
                'is_active' => 1
            ],
            [
                'user_type_code' => 'UT003',
                'user_type_name' => 'Vendor',
                'slug' => 'vendor',
                'is_active' => 1
            ],
            [
                'user_type_code' => 'UT004',
                'user_type_name' => 'Store',
                'slug' => 'store',
                'is_active' => 1
            ],
            [
                'user_type_code' => 'UT005',
                'user_type_name' => 'Warehouse',
                'slug' => 'warehouse',
                'is_active' => 1
            ]
        ];

        DB::table('user_types')->insert($userTypes);
    }
}
