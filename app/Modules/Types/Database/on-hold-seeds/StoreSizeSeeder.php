<?php
namespace  App\Modules\Types\Database\seeds;

use Illuminate\Database\Seeder;
use App\Modules\User\Models\User;
use App\Modules\Types\Models\StoreSize;

class StoreSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminUser = User::whereHas('userType',function($query){
            $query->where('slug','super-admin');
        })->first();
        $storeSizes = [
          [
              'store_size_code' => 'SSZ001',
              'store_size_name' => ' Small Sized',
              'slug' => 'small-sized',
              'is_active' => 1,
              'created_by' => $superAdminUser->user_code,
              'updated_by' => $superAdminUser->user_code,
          ]
        ];


        foreach ($storeSizes as $storeSize){
            StoreSize::updateOrCreate($storeSize);
        }


    }
}
