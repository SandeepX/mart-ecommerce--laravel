<?php

namespace  App\Modules\Types\Database\seeds;

use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Modules\Types\Models\StoreType;
use Illuminate\Support\Facades\DB;

class StoreTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('store_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        StoreType::create(array(
            'store_type_code' => 'SST001',
            'store_type_name' => 'Allpasal Mart',
            'store_type_slug' => 'allpasal-mart',
            'is_active' => '1',
            'created_by' => 'U00000001',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            ));
        StoreType::create(array(
            'store_type_code' => 'SST002',
            'store_type_name' => 'AllPasal Mini Mart',
            'store_type_slug' => 'allpasal-mini-mart',
            'is_active' => '1',
            'created_by' => 'U00000001',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ));
    }
}
