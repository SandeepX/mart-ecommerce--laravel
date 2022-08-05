<?php
namespace  App\Modules\SalesManager\Database\seeds;

use App\Modules\SalesManager\Models\Manager;
use App\Modules\SalesManager\Models\ManagerStore;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Exception;

class ManagerCodeInManagerStoreTableSeeder extends Seeder
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
            $managerStores = ManagerStore::orderBy('id','desc')->get();
            foreach ($managerStores as $managerStore){
                $manager = Manager::where('user_code',$managerStore->manager_code)->first();
                if(!$manager){
                    $managerStore->delete();
                }else{
                   $managerStore->update(['manager_code'=>$manager->manager_code]);
                }
            }
            DB::commit();
        }catch (Exception $exception){
             DB::rollBack();
            echo $exception->getMessage();
        }
    }
}
