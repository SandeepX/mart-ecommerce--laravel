<?php

namespace  App\Modules\Types\Database\seeds;

use Illuminate\Database\Seeder;
use App\Modules\Types\Models\UserType;
use App\Modules\User\Models\User;
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
                'namespace' => 'App\Modules\User\Models\User',
                'is_active' => 1
            ],
            [
                'user_type_code' => 'UT002',
                'user_type_name' => 'Admin',
                'slug' => 'admin',
                'namespace' => 'App\Modules\User\Models\User',
                'is_active' => 1
            ],
            [
                'user_type_code' => 'UT003',
                'user_type_name' => 'Vendor',
                'slug' => 'vendor',
                'namespace' => 'App\Modules\Vendor\Models\Vendor',
                'is_active' => 1
            ],
            [
                'user_type_code' => 'UT004',
                'user_type_name' => 'Store',
                'slug' => 'store',
                'namespace' => 'App\Modules\Store\Models\Store',
                'is_active' => 1
            ],
            [
                'user_type_code' => 'UT005',
                'user_type_name' => 'Warehouse Admin',
                'slug' => 'warehouse-admin',
                'namespace' => 'App\Modules\User\Models\User',
                'is_active' => 1
            ],
            [
                'user_type_code' => 'UT006',
                'user_type_name' => 'Warehouse User',
                'slug' => 'warehouse-user',
                'namespace' => 'App\Modules\User\Models\User',
                'is_active' => 1
            ],
            [
                'user_type_code' => 'UT007',
                'user_type_name' => 'Sales Manager',
                'slug' => 'sales-manager',
                'namespace' => 'App\Modules\SalesManager\Models\Manager',
                'is_active' => 1
            ],

            [
                'user_type_code' => 'UT008',
                'user_type_name' => 'b2c Customer',
                'slug' => 'b2c-customer',
                'namespace' => 'App\Modules\User\Models\User',
                'is_active' => 1
            ],
            [
                'user_type_code' => 'UT009',
                'user_type_name' => 'Support Admin',
                'slug' => 'support-admin',
                'namespace' => 'App\Modules\User\Models\User',
                'is_active' => 1
            ],

        ];

        foreach ($userTypes as $userType){
            UserType::updateOrCreate($userType);
        }
    }
}
