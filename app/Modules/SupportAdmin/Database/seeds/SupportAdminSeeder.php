<?php

namespace  App\Modules\SupportAdmin\Database\seeds;

use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class SupportAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();

       $user->updateOrCreate([
           'user_type_code' => 'UT009',
           'login_email' => 'support@allpasal.com',
       ],[
           'user_code' => $user::generateUserCode(),
           'name' => 'Support Admin',
           'login_phone' => '9865852916',
           'password' => bcrypt('support123'),
           'created_by' => 'U00000001',
           'updated_by' => 'U00000001'
       ]);
    }
}

