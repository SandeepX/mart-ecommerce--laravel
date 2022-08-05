<?php

namespace App\Modules\RolePermission\Database\seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
      //  DB::table('roles')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        $defaultGuard = 'web';

        $roles = [
            [
                'name' => 'Super Admin',
                'description' => 'super admin',
                'for_user_type' => 'admin',
            ],

        ];

        //  DB::table('roles')->insert($roles);

        $roles = collect($roles)->map(function ($role) use($defaultGuard) {
//            return [
//                'name' => ucwords($role['name']),
//                'slug' =>Str::slug($role['name'],'-'),
//                'is_closed' =>1,
//                'description' => strtolower($role['description']),
//                'guard_name' => $defaultGuard,
//                'for_user_type' => $role['for_user_type'],
//                'created_by' =>'U00000001',
//                'updated_by' =>'U00000001',
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now(),
//            ];
            \Spatie\Permission\Models\Role::updateOrCreate([
                'name'=>$role['name'],
                'slug'=>Str::slug($role['name'],'-'),
            ],
            ['is_closed' =>1,
                'description' => strtolower($role['description']),
                'guard_name' => $defaultGuard,
                'for_user_type' => $role['for_user_type'],
                'created_by' =>'U00000001',
                'updated_by' =>'U00000001',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),]);
        });



    }
}
