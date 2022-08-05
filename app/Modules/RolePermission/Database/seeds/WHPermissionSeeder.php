<?php

namespace App\Modules\RolePermission\Database\seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class WHPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        // DB::table('roles')->truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        $defaultGuard = 'web';

        $roles = [
            [
                'name' => 'Warehouse Admin',
                'description' => 'warehouse admin',
                'for_user_type' => 'warehouse',
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

            $permissionIdsForWarehouse = Permission::where('permission_for','warehouse')->pluck('id');
            $role = Role::where('slug','warehouse-admin')->first();
            $role->permissions()->syncWithoutDetaching($permissionIdsForWarehouse);
        });
       DB::commit();
    }
}
