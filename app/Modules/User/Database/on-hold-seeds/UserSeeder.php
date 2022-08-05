<?php

namespace  App\Modules\User\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'user_code' => 'U00000001',
                'user_type_code' => 'UT001',
                'name' => 'Super Admin',
                'login_email' => 'admin@gmail.com',
                'login_phone' => '9808590013',
                'password' => bcrypt('secret'),
                'created_by' => 'U00000001',
                'updated_by' => 'U00000001'
            ],

            // [
            //     'user_code' => 'U00000002',
            //     'user_type_code' => 'UT003',
            //     'name' => 'Vendor 1',
            //     'login_email' => 'vendor@gmail.com',
            //     'login_phone' => '9808590014',
            //     'password' => bcrypt('secret'),
            //     'created_by' => 'U00000001',
            //     'updated_by' => 'U00000001'
            // ]
        ]; 
        
        DB::table('users')->insert($users);
    }
}
