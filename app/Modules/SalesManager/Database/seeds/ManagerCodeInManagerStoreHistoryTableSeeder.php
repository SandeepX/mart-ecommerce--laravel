<?php
namespace  App\Modules\SalesManager\Database\seeds;

use App\Modules\SalesManager\Models\Manager;
use App\Modules\SalesManager\Models\ManagerStoreHistroy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Exception;

class ManagerCodeInManagerStoreHistoryTableSeeder extends Seeder
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
            $managerStoreHistories = ManagerStoreHistroy::orderBy('id','DESC')->get();

            foreach ($managerStoreHistories as $managerStoreHistory){
                $manager = Manager::where('user_code',$managerStoreHistory->manager_code)->first();
                if($manager){
                    $managerStoreHistory->update(['manager_code'=>$manager->manager_code]);
                }
            }
            DB::commit();
        }catch (Exception $exception){
          DB::rollBack();
          echo $exception->getMessage();
        }
    }
}
