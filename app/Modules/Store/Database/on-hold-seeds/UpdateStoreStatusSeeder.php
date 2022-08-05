<?php
namespace App\Modules\Store\Database\seeds;
use Illuminate\Database\Seeder;
use DB;

class UpdateStoreStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stores_detail')->where('is_active', 1)->update(['status' => 'approved']);
    }
}
