<?php


namespace App\Modules\Store\Database\seeds;


use App\Modules\Store\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddStoreFullLocationSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            DB::beginTransaction();

            $stores = Store::get();
            foreach($stores as $store)
            {
                $store->store_full_location = $store->getFullLocationPath();
                $store->save();
            }
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            echo $exception;
        }
    }
}
