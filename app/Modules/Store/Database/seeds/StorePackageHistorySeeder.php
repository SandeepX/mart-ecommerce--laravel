<?php


namespace App\Modules\Store\Database\seeds;

use App\Modules\Store\Models\Store;
use App\Modules\Store\Models\StorePackageTypes\StorePackageHistory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StorePackageHistorySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        try{

            $storesToUpdate = [
                [
                    'store_code' => 'S1723',
                    'store_type_package_history_code' => 'STPHC1012'
                ],
                [
                    'store_code' => 'S1826',
                    'store_type_package_history_code' => 'STPHC1013',
                ]
            ];

            DB::beginTransaction();

            foreach($storesToUpdate as $storeToUpdate){
                $store = Store::where('store_code',$storeToUpdate['store_code'])->firstorFail();
                $storeToUpdate['store_type_code'] = $store->store_type_code;
                $storeToUpdate['from_date'] = $store->created_at;
                $storeToUpdate['to_date'] =  Carbon::now()->subDays(2);

                $storeToUpdate['remarks'] = 'package updated manually';
                $storeToUpdate['created_by'] = 'U00000001';

                StorePackageHistory::create($storeToUpdate);

               echo "\033[32m"."Package History Saved of ".$store->store_name." (".$storeToUpdate['store_code'].")"."\n";
            }
           // dd('done');
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            echo $exception->getMessage().$exception->getLine();
        }








    }

}
