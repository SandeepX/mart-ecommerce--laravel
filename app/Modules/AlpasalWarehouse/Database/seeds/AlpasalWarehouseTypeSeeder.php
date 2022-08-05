<?php

namespace  App\Modules\AlpasalWarehouse\Database\seeds;

use App\Modules\AlpasalWarehouse\Models\AlpasalWarehouseType;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlpasalWarehouseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warehouseTypes = [
            [
                'warehouse_type_code' => 'AWT00001',
                'warehouse_type_name' => 'closed',
                'slug' => 'closed',
                'warehouse_type_key' => 'closed',
                'is_closed' => 1,
                'created_by' => User::first()->user_code,
                'updated_by' => User::first()->user_code,
            ],

            [
                'warehouse_type_code' => 'AWT00002',
                'warehouse_type_name' => 'open',
                'slug' => 'open',
                'warehouse_type_key' => 'open',
                'is_closed' => 1,
                'created_by' => User::first()->user_code,
                'updated_by' => User::first()->user_code,
            ]
        ];
        foreach($warehouseTypes as $warehouseType){
            AlpasalWarehouseType::updateOrCreate($warehouseType);

        }
    }
}
